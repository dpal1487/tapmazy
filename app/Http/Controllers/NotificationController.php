<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Http\Resources\NotificationsResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public $notifications = [];

    public function index(Request $request)
    {
        if ($request->limit == 10) {
            $notifications = Notification::orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            return response()->json(['success' => true, 'data' => NotificationsResource::collection($notifications)]);
        } else {
            $notifications = Notification::orderBy('created_at', 'desc')->get();
            return Inertia::render('Notification/Index', [
                'notifications' => NotificationsResource::collection($notifications)
            ]);
        }
    }

    public function headerNotifications(Request $request)
    {
        if ($this->companyId()) {
            $this->notifications = Notification::where(['company_id' => $this->companyId()])->orderBy('created_at', 'desc')->limit($request->limit)->get();
        }
        return NotificationResource::collection($this->notifications);
    }
}
