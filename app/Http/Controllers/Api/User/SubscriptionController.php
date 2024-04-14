<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{

    public function subscribe(Request $request)
    {
        $data = $this->validate($request, [
            "package_id" => "required|exists:packages,id"
        ]);

        $user = auth()->user();
        $package = Package::findOrFail($data["package_id"]);

        $subscription = Subscription::create([
            "user_id" => $user->id,
            "package_id" => $package->id,
            "expired_at" => now()->addDays($package->days)
        ]);



        return response()->json([
            "data" => [
                "message" => "subscribed successfully"
            ]
        ]);
    }
}
