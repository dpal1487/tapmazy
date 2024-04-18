<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanListResource extends JsonResource
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
            'project' => $this->project,
            'allowed_projects'  => $this->allowed_projects,
            'allowed_invoices'  => $this->allowed_invoices,
            'allowed_users'  => $this->allowed_users,
            'allowed_clients'  => $this->allowed_clients,
            'allowed_suppliers'  => $this->allowed_suppliers,
            'slug'  => $this->slug,
            'name'  => $this->plan_name,
            'sort_description'  => $this->sort_description,
            'description'  => $this->description,
            'plan_type'  => $this->plan_type,
            'status' => $this->status,
            'stripe_plan_id' => $this->stripe_plan_id,
            'price' => $this->plan_price,
            'currency' => $this->currency,
            'currency_value' => $this->currency_value,
            'created_at' => date('d-m-y h:s A', strtotime($this->created_at)),
        ];
    }
}
