<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrencyResource;
use App\Http\Resources\PlanListResource;
use App\Http\Resources\PlanResource;
use App\Http\Resources\SubscriptionListResource;
use App\Http\Resources\User\UserResource;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Stripe\Coupon;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;
use App\Models\Subscription;

class PlanController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }
    public function index()
    {
        $plans = Plan::all();
        $subscription = Subscription::pluck('user_id')->all();
        return Inertia::render('Plan/Index', [
            'plans' => PlanListResource::collection($plans),
            'subscription' => $subscription,
        ]);
    }
    public function show($id, Request $request)
    {

        $plan = Plan::find($id);
        return Inertia::render('Plan/Show', [
            'plan' => new PlanResource($plan),
            'user' => new UserResource(auth()->user()),
        ]);
    }
    public function getUser(Request $request)
    {
        $customes = new User();
        if (!empty($request->q)) {
            $customes = $customes->where('name', 'like', "%$request->q%");
        }
        return response()->json([
            'customes' => UserResource::collection($customes->get())
        ]);
    }

    public function subscription(Request $request)
    {
        $plan = Plan::find($request->plan);

        $subscription = $request->user()->newSubscription($request->plan, $plan->stripe_plan)
            ->create($request->token);

        return view("subscription_success");
    }
    public function create()
    {
        $currencies = Currency::get();
        return Inertia::render('Plan/Form', [
            'currencies' => CurrencyResource::collection($currencies),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:plans,plan_name',
            'sort_description' => 'required',
            'price' => 'required',
            'currency_value' => '',
            'interval' => 'required',
            'coupon'    => 'required',
            'allowed_projects' => 'required',
            'allowed_invoices' => 'required',
            'allowed_users' => 'required',
            'allowed_clients' => 'required',
            'allowed_suppliers' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first(), 'success' => false]);
        }
        $product = Product::create([
            'name' => $request->name,
            'type' => 'service', // You can use 'service' or 'good' based on your product
        ]);

        $price = Price::create([
            'product' => $product->id,
            'unit_amount' => $request->price * 100, // Amount in cents (adjust as needed)
            'currency' => 'USD', // Currency
            'recurring' => [
                'interval' => $request->interval, // You can use 'day', 'week', 'month', or 'year'
            ],
        ]);
        $coupon = Coupon::create([
            'name' => $request->coupon,
            'percent_off' => $request->percent_off,
            'duration' => 'once', // Adjust duration as needed (once, forever, repeating)
            'duration_in_months' => null, // Specify for repeating duration
            'currency' => 'usd', // Adjust currency as needed
        ]);
        $plan = Plan::create([
            'stripe_plan_id' => $price->id,
            'project_id' => $product->id,
            'plan_name' => $request->name,
            'slug' => Str::slug($request->name),
            'allowed_projects' => $request->allowed_projects,
            'allowed_invoices' => $request->allowed_invoices,
            'allowed_users' => $request->allowed_users,
            'allowed_clients' => $request->allowed_clients,
            'allowed_suppliers' => $request->allowed_suppliers,
            'sort_description' => $request->sort_description,
            'description' => json_encode($request->items),
            'status' => 1,
            'plan_price' => $request->price,
            'plan_type' => $request->interval,
            'currency_value' => 'USD',
            'coupon_name' => $request->coupon,
            'coupon_id' => $coupon->id,
            'percent_off' => $request->percent_off,

        ]);
        if ($plan) {
            return redirect('/plans')->with('flash', createMessage('Plan'));
        }
        return redirect('/plan')->with('flash', errorMessage());
    }

    public function edit($id)
    {
        $plan = Plan::find($id);
        $currencies = Currency::get();
        return Inertia::render('Plan/Form', [
            'plan' => new PlanResource($plan),
            'currencies' => CurrencyResource::collection($currencies),
        ]);
    }
    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'sort_description' => 'required',
            'price' => 'required',
            'currency_value' => 'required',
            'interval' => 'required',
            'coupon'    => '',

        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first(), 'success' => false]);
        }

        $plan = Plan::where(['id' => $request->id])->first();
        $product =  Product::retrieve($plan->project_id);
        // Retrieve the coupon by its ID

        if ($plan->coupon_id) {
            $coupon = Coupon::retrieve($plan->coupon_id);
            if (!$coupon) {
                $coupon = Coupon::create([
                    'name' => $request->coupon,
                    'percent_off' => $request->percent_off,
                    'duration' => 'once', // Adjust duration as needed (once, forever, repeating)
                    'duration_in_months' => null, // Specify for repeating duration
                    'currency' => 'usd', // Adjust currency as needed
                ]);
            }
        } else {
            $coupon = Coupon::create([
                'name' => $request->coupon,
                'percent_off' => $request->percent_off,
                'duration' => 'once', // Adjust duration as needed (once, forever, repeating)
                'duration_in_months' => null, // Specify for repeating duration
                'currency' => 'usd', // Adjust currency as needed
            ]);
        }
        $update = $plan->update([
            'plan_name' => $request->name,
            'slug' => Str::slug($request->name),
            'project_id' => $product->id,
            'allowed_projects' => $request->price == '100' ?   50 : 100,
            'allowed_invoices' => $request->price == '100' ?   50 : 100,
            'allowed_users' => $request->price == '100' ?   50 : 100,
            'allowed_clients' => $request->price == '100' ?   50 : 100,
            'allowed_suppliers' => $request->price == '100' ?   50 : 100,
            'sort_description' => $request->sort_description,
            'description' => json_encode($request->items),
            'status' => 1,
            'plan_price' => $request->price,
            'plan_type' => $request->interval,
            'currency_value' => $request->currency_value,
            'coupon_name'    => $request->coupon,
            'percent_off' => $request->percent_off,
            'coupon_id' => $coupon->id,
        ]);
        if ($update) {
            return redirect('/plans')->with('flash', updateMessage('Plan'));
        }
        return redirect('/plans')->with('flash', errorMessage());
    }

    public function destroy($id)
    {
        $plan = Plan::find($id);
        if ($plan->delete()) {
            return response()->json(deleteMessage('Plan'));
        }
        return response()->json(errorMessage());
    }

    public function successPage()
    {
        return Inertia::render('Plan/Page/Success');
    }


    public function errorPage()
    {
        return Inertia::render('Plan/Page/Error');
    }
}
