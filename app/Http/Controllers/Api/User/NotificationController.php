<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Quotation;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $paginatedNotifications = Notification::where("notifiable_id", $user->id)->latest()->paginate(10);
        $notifications = $paginatedNotifications
            ->map(function ($notification) use ($user) {
                $data = $notification->toArray();

                if (isset($data["data"]["quotation_id"]))
                    $data["data"] = [
                        "sender" => User::find($data["data"]["sender_id"])->load("supplier", "buyer"),
                        "quotation" => Quotation::find($data["data"]["quotation_id"])
                    ];

                else if (isset($data["data"]["service_id"]))
                    $data["data"] = [
                        "sender" => User::find($data["data"]["sender_id"])->load("supplier", "buyer"),
                        "service" => Service::find($data["data"]["service_id"])
                    ];

                return $data;
            });

        return response()->json([
            "data" => [
                "data" => $notifications,
                "current_page" => $paginatedNotifications->currentPage(),
                "last_page" => $paginatedNotifications->lastPage(),
                "per_page" => $paginatedNotifications->perPage(),
                "total" => $paginatedNotifications->total(),
            ]
        ]);
    }

    public function read($notificationId)
    {
        $user = auth()->user();
        $notification = $user->notifications()->findOrFail($notificationId);

        if ($notification->read_at == null)
            $notification->markAsRead();

        return response()->json([
            "data" => $notification
        ]);
    }

    public function notificationsCount()
    {
        $user = auth()->user();
        $data["notifications_count"] = $user->notifications()->count();
        $data["unread_notifications_count"] = $user->unreadNotifications()->count();


        return response()->json([
            "data" => $data
        ]);
    }

    public function readAllNotifications(Request $request)
    {
        $user = auth()->user();
        $user->unreadNotifications->markAsRead();

        return response()->json([
            "data" => null,

        ]);
    }
}
