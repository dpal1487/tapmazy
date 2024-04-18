<?php

namespace App\Http\Controllers;

use App\Http\Resources\StateResource;
use App\Models\State;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function states($id){
        $states = new State();
        if($id){
            $states=$states->where(['country_id'=>$id]);
        }
        return response()->json(StateResource::collection($states->get()));
    }
}
