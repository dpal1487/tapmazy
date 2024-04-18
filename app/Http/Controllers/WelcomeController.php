<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlanListResource;
use App\Models\Plan;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Application;

class WelcomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome',[
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'plans'=>PlanListResource::collection(Plan::latest()->get()),
        ]);
    }
}
