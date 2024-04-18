<?php

namespace App\Http\Controllers;

use App\Helpers\StripeHelper;
use App\Http\Resources\PlanResource;
use Error;
use Exception;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Plan;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;
use App\Models\Subscription as SubscriptionModel;
use Stripe\{Coupon, Stripe, Customer, Price, Webhook, Product, Event, Invoice, PaymentIntent, PaymentMethod, Subscription};
use App\Http\Resources\SubscriptionListResource;
use App\Http\Resources\SubscriptionResource;
use App\Models\StripeUser;
use App\Models\Transaction;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function index(Request $request)
    {
        $subscriptions = new SubscriptionModel;
        if (!empty($request->q)) {
            $subscriptions = $subscriptions->whereHas('user', function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $request->q . '%')
                    ->orWhere('last_name', 'like', '%' . $request->q . '%');
            })
                ->orWhereHas('plan', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request . '%')
                        ->orWhere('price', 'like', '%' . $request->q . '%');
                });
        }

        $subscriptions = $subscriptions->paginate(10)->appends($request->all());
        return Inertia::render('Subscription/Index', [
            'subscriptions' => SubscriptionListResource::collection($subscriptions),
        ]);
    }

    public function checkout(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
            $plan = Plan::find($request->plan_id);
            if (!isset($plan->plan_name) || !isset($plan->plan_price)) {
                throw new Exception("Plan data is invalid.");
            }
            $planPriceInCents = 100 * $plan->plan_price;
            if ($planPriceInCents <= 0) {
                throw new Exception("Invalid plan price.");
            }
            $user = auth()->user();
            DB::beginTransaction();
            $session = Session::create([
                'payment_method_types' => ['card'],
                'subscription_data' => [
                    'trial_period_days' => 2, // Replace with the desired trial period duration in days
                ],
                'line_items' => [
                    [
                        'price' => $plan->stripe_plan_id,
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'subscription',
                'allow_promotion_codes' => true,

                // 'coupon' => $couponCode, // Apply coupon if provided
                'metadata' => [
                    'coupon_code' => 'AB123ACC', // Pass coupon code as metadata
                ],
                'success_url' => route(name: 'payment.success', absolute: true) . "?session_id={CHECKOUT_SESSION_ID}",
                'cancel_url' => route(name: 'payment.cancel', absolute: true),
            ]);

            // $paymentIntent = PaymentIntent::create([
            //     'amount' => $session->amount_total * 100, // Stripe requires amount in cents
            //     'currency' => $session->currency,
            //     'description' => 'Payment for your order',
            //     'payment_method' => $$session->payment_method_types[0],
            //     'confirm' => true,
            // ]);

            // Update transaction status in database
            // $transaction = Transaction::create([
            //     'stripe_payment_id' => $paymentIntent->id,
            //     'status' => $paymentIntent->status,
            //     'amount' => $request->amount,
            //     'payment_mode' => $paymentIntent->payment_method
            // ]);

            $subscription = SubscriptionModel::create([
                'user_id' => $user->id,
                'quantity' => 1,
                'plan_id' => $request->plan_id,
                'session_id' => $session->id,
                'stripe_subscription_id' => '',
                'status' => 'unpaid', // or any other valid enum value
                'current_period_start' => $session->current_period_start,
                'current_period_end' => $session->current_period_end,
            ]);
            if ($subscription) {
                DB::commit();
                return response()->json([
                    'session_id' => $session->id,
                    'url' => $session->url,
                    'session' => $session,
                ]);
            } else {
                Log::error('Subscription creation failed.');
                return response()->json(errorMessage());
            }
        } catch (Error $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    public function successPage(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $sessionId = $request->session_id;
        try {
            $session = Session::retrieve($sessionId);
            if (!$session) {
                throw new NotFoundHttpException();
            }
            $customer = Customer::retrieve($session->customer);
            if ($customer) {
                StripeUser::create([
                    'user_id' => auth()->user()->id,
                    'stripe_customer_id' => $customer->id,
                ]);
            }
            $subscription = SubscriptionModel::where('session_id', $session->id)->first();
            $subscriptionDetails =  Subscription::retrieve($session->subscription);

           
            $paymentMethod = PaymentMethod::retrieve($subscriptionDetails->default_payment_method);
            // $paymentIntent = PaymentIntent::create([
            //     'amount' => $session->amount_total * 100, // Stripe requires amount in cents
            //     'currency' => $session->currency,
            //     'description' => 'Payment for your order',
            //     'payment_method' => $$session->payment_method_types[0],
            //     'confirm' => true,
            // ]);

            // echo "<pre>";
            // echo $session;
            // echo "</pre>";
            // exit();

            // Update transaction status in database
            $transaction = Transaction::create([
                'stripe_payment_id' => $paymentMethod->id,
                'status' => $session->payment_status,
                'amount' => $session->amount_total,
                'payment_mode' => $session->payment_method_types[0]
            ]);
            $trialStart = Carbon::createFromTimestamp($subscriptionDetails->trial_start);
            $trialEnd = Carbon::createFromTimestamp($subscriptionDetails->trial_end);
            $diffInDays = $trialEnd->diffInDays($trialStart);
            if (!$subscription) {
                throw new NotFoundHttpException();
            }
            if ($subscription->status === 'unpaid') {
                SubscriptionModel::where('session_id', $session->id)->update([
                    'stripe_subscription_id' => $subscriptionDetails->id,
                    'status' => 'active',
                    'current_period_end' => Carbon::createFromTimestamp($subscriptionDetails->current_period_end)->toDateTimeString(),
                    'trial_start' => Carbon::createFromTimestamp($subscriptionDetails->trial_start)->toDateTimeString(),
                    'trial_end' => Carbon::createFromTimestamp($subscriptionDetails->trial_end)->toDateTimeString(),
                    'trial_period_days' => $diffInDays,
                ]);
            }
            // return Inertia::render('Plan/Page/Success', [
            //     'customer' => $customer,
            //     'subscription' => $subscriptionDetails,
            //     'session' => $session,
            // ]);
        } catch (Exception $e) {
            throw new NotFoundHttpException();
        }
    }
    public function cancelPage(Request $request)
    {
        return Inertia::render('Plan/Page/Cancel', []);
    }
    public function webhook()
    {
        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response('', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('', 400);
        }
        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $session = $event->data->object;

                $sessionId = $session->id;

                $subscription = SubscriptionModel::where('session_id', $session->id)->first();
                $subscriptionDetails =  Subscription::retrieve($session->subscription);


                if ($subscription && $subscription->status === 'unpaid') {

                    SubscriptionModel::where('session_id', $session->id)->update([
                        'stripe_subscription_id' => $subscriptionDetails->id,
                        'status' => 'active',
                        'current_period_end' => Carbon::createFromTimestamp($subscriptionDetails->current_period_end)->toDateTimeString(),
                        'trial_start' => Carbon::createFromTimestamp($subscriptionDetails->trial_start)->toDateTimeString(),
                        'trial_end' => Carbon::createFromTimestamp($subscriptionDetails->trial_end)->toDateTimeString(),
                        'trial_period_days' => $subscriptionDetails->trial_period_days,
                    ]);
                }

                // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }
        return response('');
    }




    public function show($id)
    {
        try {
            $userSubscription = SubscriptionModel::find($id);
            $subscription = Subscription::retrieve($userSubscription->stripe_subscription_id);
            $customer = Customer::retrieve($subscription->customer);
            $paymentMethod = PaymentMethod::retrieve($subscription->default_payment_method);

            $events = Event::all();
            $subscriptionEvents = [];
            foreach ($events as $event) {
                $data = $event->data->object;
                if (isset($data->subscription) && $data->subscription === $subscription->id) {
                    $subscriptionEvents[] = $event;
                }
            }
            // Return the subscription-related events data
            $invoiceId = $subscription->latest_invoice;

            $invoice = Invoice::retrieve($invoiceId);
            $invoiceDetails = [
                'id' => $invoice->id,
                'amount_due' => $invoice->amount_due,
                'currency' => $invoice->currency,
                'number' => $invoice->number,
                'status' => $invoice->status,
                'period' => $invoice,
                'invoice_pdf' => $invoice->invoice_pdf,
                // Add more invoice details as needed
            ];

            return Inertia::render('Subscription/Show', [
                'subscription' => new SubscriptionResource($userSubscription),
                'invoice' => $invoiceDetails,
                'events' => $subscriptionEvents,
                'customer' => $customer,
                'product' => Product::retrieve($subscription->plan->product),
                'card_details' => $paymentMethod->card
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Handle Stripe API errors
            return ['error' => $e->getMessage()];
        } catch (\Exception $e) {
            // Handle other errors
            return ['error' => $e->getMessage()];
        }
    }

    public function getPlan($id)
    {
        $plans = Plan::all();
        $subscription = SubscriptionModel::where('id', $id)->select('plan_id', 'id')->first();
        $user = auth()->user();
        $activePlanId = $user->stripeUser->activeSubscription->plan_id;
        return Inertia::render('Subscription/UpdatePlan', [
            'plans' => PlanResource::collection($plans),
            'subscription' => $subscription,
            'activePlanId' => $activePlanId,
        ]);
    }

    public function updatePlan(Request $request)
    {
        try {
            // Get the authenticated user
            $user = auth()->user();

            // Begin a database transaction
            DB::beginTransaction();

            // Get the Stripe subscription ID and local subscription ID
            $subscriptionStripeId = $user->stripeUser->activeSubscription->stripe_subscription_id;
            $subscriptionId = $user->stripeUser->activeSubscription->id;

            // Retrieve the current subscription from Stripe
            $subscription = StripeHelper::retrieveSubscription($subscriptionStripeId);
            if (!$subscription['status']) {
                // If retrieval fails, return error response
                return response()->json(['status' => false, 'message' => $subscription['error']]);
            }

            // Retrieve the plan ID from the request
            $planId = $request->id;

            // Retrieve the corresponding Stripe plan ID from the local database
            $planRes = Plan::select('stripe_plan_id')->where('id', $planId)->first();

            // Prepare data for subscription update
            $data = [
                'items' => [
                    [
                        'id'    => $subscription['data']->items->data[0]->id,
                        'price' => $planRes->stripe_plan_id,
                    ],
                ],
            ];

            // Update the subscription on Stripe
            $updateSubscriptionResult = StripeHelper::updateSubscription($subscriptionStripeId, $data);
            if (!$updateSubscriptionResult['status']) {
                // If update fails, return error response
                return response()->json(['status' => false, 'message' => $updateSubscriptionResult['error']]);
            }

            // Calculate trial period days
            $trialStart = Carbon::createFromTimestamp($updateSubscriptionResult['data']->trial_start);
            $trialEnd = Carbon::createFromTimestamp($updateSubscriptionResult['data']->trial_end);
            $diffInDays = $trialEnd->diffInDays($trialStart);

            // Update the local subscription record
            $sub = SubscriptionModel::findOrFail($subscriptionId);
            $sub->update([
                'plan_id'               => $planId,
                'current_period_start'  => Carbon::createFromTimestamp($updateSubscriptionResult['data']->current_period_start)->toDateTimeString(),
                'current_period_end'    => Carbon::createFromTimestamp($updateSubscriptionResult['data']->current_period_end)->toDateTimeString(),
                'plan_amount'           => $updateSubscriptionResult['data']->plan->amount / 100,
                'trial_start'           => $trialStart->toDateTimeString(),
                'trial_end'             => $trialEnd->toDateTimeString(),
                'trial_period_days'     => $diffInDays,
            ]);

            // Commit the database transaction
            DB::commit();

            // Return success response
            return response()->json(['success' => true, 'message' => 'Your subscription updated successfully']);
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();

            // Log the error for debugging
            logger()->error($e);

            // Return error response
            return response()->json(['status' => false, 'message' => 'Failed to update subscription. Please try again later.']);
        }
    }
    public function cancelSubscription(Request $request)
    {
        $sub_id = $request->subscription_id;
        $subRow = SubscriptionModel::where('id', $sub_id)->first();
        $subscription = StripeHelper::cancelSubscription($subRow->stripe_subscription_id);
        if ($subscription['status'] == false) {
            $exception = array('status' => false, 'message' => $subscription['error']);
            return response()->json($exception);
        }
        SubscriptionModel::where('id', $sub_id)->update(['status' => 'canceled']);
        return response()->json(['status' => true, 'message' => 'Your subscription cancel successfully']);
    }



    public function userSubscription($data, $request = NULL)
    {
        $subscriptionResult = $data;
        $startDate = date('Y-m-d H:i:s', $subscriptionResult['data']->current_period_start);
        $endDate   = date('Y-m-d H:i:s', $subscriptionResult['data']->current_period_end);
        $createSubscrArray = array(
            'user_name'       => $request->user_name,
            'email'           => $request->email,
            'phone_number'    => $request->phone_number,
            'plan_id'         => $request->plan_id,
            'subscription_id' => $subscriptionResult['data']->id,
            'stripe_user_id'  => $request->stripe_user_id,
            'start_date'      => $startDate,
            'end_date'        => $endDate,
            'plan_amount'     => $subscriptionResult['data']->plan->amount / 100,
            'current_status'  => 'unpaid',
        );
        SubscriptionModel::create($createSubscrArray);
    }
}
