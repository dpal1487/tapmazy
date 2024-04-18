<?php

namespace App\Http\Controllers;

use App\Models\CompanyUser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function uid()
    {
        $user = Auth::user();
        return $user->id;
    }
    public function companyId()
    {
        $user = Auth::user();
        return $user->company_id;
    }
}
