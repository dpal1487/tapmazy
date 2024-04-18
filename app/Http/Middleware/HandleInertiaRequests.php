<?php

namespace App\Http\Middleware;

use App\Http\Resources\MenuListResource;
use App\Http\Resources\NotificationResource;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Menu;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';
    protected $company = [];
    protected $notificationCount = [];
    protected $user = [];
    protected $email = [];
    protected $role = [];
    protected $address = [];
    protected $status = [
        [
            'name' => 'All',
            'value' => 'all',
        ],
        [
            'name' => 'Active',
            'value' => '1',
        ],
        [
            'name' => 'Inactive',
            'value' => '0',
        ],
    ];

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        if (Auth::check()) {
        }

        $this->user = $request->user();
        $this->role = $request->user() ? $request->user()->role?->role?->pluck('slug') : [];
        return array_merge(parent::share($request), [
            
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'user' => $this->user,
                    'location' => $request->url(),
                    'status' => $this->status,
                    'role' => $this->role,
                    'menus' => MenuListResource::collection(Menu::with(['items' => function ($q) {
                        $q->orderBy('title', 'asc');
                    }])->get()),
                    'notification' => [
                        'count' => 0,
                    ],
                    'flash' => [
                        'message' => fn () => $request->session()->get('message'),
                    ],

                ]);
            },
            'recaptcha_site_key' => config('services.google_recaptcha.site_key'),
        ]);
    }
}
