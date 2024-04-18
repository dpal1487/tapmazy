<?php

namespace App\Http\Resources;

use App\Models\SupplierSurvey;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       $collection = collect(SupplierSurvey::where('project_id', $this->id)->get());
        $terminate = $collection->where('status', 'terminate');
        $complete = $collection->where('status', 'complete');
        $security_terminate = $collection->where('status', 'security-terminate');
        $incomplete = $collection->where('status', NULL);
        $quotafull = $collection->where('status', 'quotafull');
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'user' => $this->user->first_name . ' ' . $this->user->last_name,
            'project_name' => $this->project_name,
            'project_type' => ucfirst($this->project_type),
            'sample_size' => $this->project_link,
            'client' => $this->client,
            'reports' => [
                'total_clicks' => count($collection),
                'completes' => count($complete),
                'terminates' => count($terminate),
                'quotafull' => count($quotafull),
                'incompletes' => count($incomplete),
                'security_terminates' => count($security_terminate),
                'total_ir' => (count($complete) > 0) ? intval((count($complete) / (count($complete) + count($terminate))) * 100) . '%' : '0%'
            ],
            'created_at' => date("d/M/Y h:i:s a", strtotime($this->created_at)),
            'status' => $this->status,
            'target' => $this->target,
        ];
    }
}
