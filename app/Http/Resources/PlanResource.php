<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'project_id' => $this->project_id,
            'plan_name' => $this->plan_name,
            'slug' => $this->slug,
            'sort_description' => $this->sort_description,
            'description' => $this->description,
            'status' => $this->status,
            'stripe_plan_id' => $this->stripe_plan_id,
            'plan_price' => $this->plan_price,
            'plan_type' => $this->plan_type,
            'currency' => $this->currency,
            'currency_value' => $this->currency_value,
            'coupon_name' => $this->coupon_name,
            'coupon_id' => $this->coupon_id,
            'percent_off' => $this->percent_off,
            'allowed_projects' => $this->allowed_projects,
            'allowed_invoices' => $this->allowed_invoices,
            'allowed_users' => $this->allowed_users,
            'allowed_clients' => $this->allowed_clients,
            'allowed_suppliers' => $this->allowed_suppliers,
            'subscription' => $this->subscription,
        ];
    }
}
