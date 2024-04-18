<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'session_id' => $this->session_id,
            'stripe_subscription_id' => $this->stripe_subscription_id,
            'status' => $this->status,
            'user_name' => $this->user->first_name . ' ' . $this->user->last_name,
            'email' => $this->user->email,
            'name' => $this->name,
            'stripe_status' => $this->status,
            'stripe_price' => $this->stripe_price,
            'quantity' => $this->quantity,
            'current_plan_start' => date('d-M-Y', strtotime($this->current_period_start)),
            'current_plan_end' => date('d-M-Y', strtotime($this->current_period_end)),
            'trial_ends_at' => $this->expires_at,
            'ends_at' => $this->ends_at,
            'plan' => $this->plan,
            'currency' => $this->plan?->currency_value,
            'plans' => $this->plans
        ];
    }
}
