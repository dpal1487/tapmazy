<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceListResource extends JsonResource
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
            'client' => $this->client,
            'invoice_number' => $this->invoice_number,
            'type' => $this->type,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'total_amount' => $this->total_amount,
            'tax_rate' => $this->tax_rate,
            'conversion_rate' => $this->conversion_rate,
            'currency' => $this->currency,
            'notes' => $this->notes,
            'status' => $this->status,
        ];
    }
}
