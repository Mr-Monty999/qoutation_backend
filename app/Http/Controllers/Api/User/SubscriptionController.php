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
        $userWallet = $user->wallet;
        $package = Package::findOrFail($data["package_id"]);

        if (!$userWallet || $userWallet->balance < $package->price) {
            return response()->json([
                "message" => trans("messages.not_enough_credit")
            ], 403);
        }

        $latestSubscription = Subscription::where("user_id", $user->id);

        if ($latestSubscription->exists() && $latestSubscription->latest()->first()->expired_at > now()) {
            return response()->json([
                "message" => trans("messages.You are already subscribed to a package")
            ], 403);
        }

        Subscription::create([
            "user_id" => $user->id,
            "package_id" => $package->id,
            "expired_at" => now()->addDays($package->days)
        ]);

        $userWallet->balance -= $package->price;
        $userWallet->save();



        return response()->json([
            "data" => [
                "message" => trans("messages.subscribed successfully")
            ]
        ]);
    }
}
