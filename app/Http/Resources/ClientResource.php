<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'display_name' => $this->display_name,
            'status' => $this->status,
            'name' => $this->client_name,
            'tax_number' => $this->tax_number,
            'website' => $this->website,
            'linkedin_profile' => $this->linkedin_profile,
            'skype_profile' => $this->skype_profile,
            'id' => $this->id,
            'description' => $this->description,
            'address' => new AddressResource($this->address),
            'country' => new CountryResource($this->country),
            'created_at' => date('d M,Y', strtotime($this->created_at)),
            'reports' => [
                [
                    'link' => "/client/{$this->id}/projects",
                    'value' => $this->projects->count(),
                    'text' => 'Total Projects',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M64 64c0-17.7-14.3-32-32-32S0 46.3 0 64V400c0 44.2 35.8 80 80 80H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H80c-8.8 0-16-7.2-16-16V64zm406.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L320 210.7l-57.4-57.4c-12.5-12.5-32.8-12.5-45.3 0l-112 112c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L240 221.3l57.4 57.4c12.5 12.5 32.8 12.5 45.3 0l128-128z"/></svg>'
                ],
                [
                    'link' => "/client/{$this->id}/projects?status=1",
                    'value' => $this->projects->where('status', 1)->count(),
                    'text' => 'Active Projects',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="#50cd89" viewBox="0 0 512 512"><path d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-111 111-47-47c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l64 64c9.4 9.4 24.6 9.4 33.9 0L369 209z"/></svg>'
                ],
                [
                    'link' => "/client/{$this->id}/projects?status=2",
                    'value' => $this->projects->where('status', 2)->count(),
                    'text' => 'Inactive Projects',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="#7239ea" viewBox="0 0 512 512"><path d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm224-72V328c0 13.3-10.7 24-24 24s-24-10.7-24-24V184c0-13.3 10.7-24 24-24s24 10.7 24 24zm112 0V328c0 13.3-10.7 24-24 24s-24-10.7-24-24V184c0-13.3 10.7-24 24-24s24 10.7 24 24z"/></svg>'

                ],
                [
                    'link' => "/client/{$this->id}/projects?status=3",
                    'value' => $this->projects->where('status', 3)->count(),
                    'text' => 'Closed Projects',
                    'icon' => '<svg fill="#f1416c" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c-9.4 9.4-9.4 24.6 0 33.9l47 47-47 47c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l47-47 47 47c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-47-47 47-47c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-47 47-47-47c-9.4-9.4-24.6-9.4-33.9 0z"/></svg>'
                ],
            ]
        ];
    }
}
