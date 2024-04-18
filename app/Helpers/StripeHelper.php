<?php

namespace App\Helpers;

use Exception;
use Stripe\Coupon;
use Stripe\Customer;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Stripe;
use Stripe\Subscription;

class StripeHelper
{

    /**
     * Function for create new stripe customer
     *
     * @param  mixed $data
     * @param  mixed $token
     * @return void
     */
    public static function createCustomer($data)
    {
        Stripe::setApiKey(ENV('STRIPE_SECRET'));

        try {
            $created = Customer::create($data);
            return [
                'status' => true,
                'error' => null,
                'data' => $created
            ];
        } catch (InvalidRequestException $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        } catch (AuthenticationException $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    /**
     * createSubscription
     *
     * @param  mixed $customer_id
     * @param  mixed $pirce_plan_id
     * @param  mixed $couponCode
     * @return void
     */
    public static function createSubscription($customer_id, $pirce_plan_id, $couponCode = NULL)
    {
        Stripe::setApiKey(ENV('STRIPE_SECRET'));

        try {
            $created = Subscription::create([
                'customer' => $customer_id,
                'items' => [
                    ['price' => $pirce_plan_id],
                ],
                'coupon' => $couponCode
            ]);

            return [
                'status' => true,
                'error' => null,
                'data' => $created
            ];
        } catch (InvalidRequestException $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        } catch (AuthenticationException $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * cancelSubscription
     *
     * @param  mixed $sub_id
     * @return void
     */
    public static function cancelSubscription($sub_id)
    {
        $stripe = new \Stripe\StripeClient(ENV('STRIPE_SECRET'));
        try {
            $cancelled = $stripe->subscriptions->cancel($sub_id);
            return [
                'status' => true,
                'error' => null,
                'data' => $cancelled
            ];
        } catch (InvalidRequestException $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        } catch (AuthenticationException $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * retrieveCoupon
     *
     * @param  mixed $data
     * @return void
     */
    public static function retrieveCoupon($coupon = null)
    {
        Stripe::setApiKey(ENV('STRIPE_SECRET'));

        try {
            $result = Coupon::retrieve(
                $coupon,
                ['expand' => ['applies_to']]
            );
            return [
                'status' => true,
                'error' => null,
                'data' => $result
            ];
        } catch (InvalidRequestException $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        } catch (AuthenticationException $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * updateSubscription
     *
     * @param  mixed $sub_id
     * @param  mixed $data
     * @return void
     */
    public static function updateSubscription($sub_id, $data = array())
    {
        Stripe::setApiKey(ENV('STRIPE_SECRET'));

        try {
            $updated = Subscription::update($sub_id, $data);
            return [
                'status' => true,
                'error' => null,
                'data' => $updated
            ];
        } catch (InvalidRequestException $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        } catch (AuthenticationException $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    public static function retrieveSubscription($sub_id)
    {
        Stripe::setApiKey(ENV('STRIPE_SECRET'));

        try {
            $retrieve = Subscription::retrieve($sub_id);
            return [
                'status' => true,
                'error' => null,
                'data' => $retrieve
            ];
        } catch (InvalidRequestException $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        } catch (AuthenticationException $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
