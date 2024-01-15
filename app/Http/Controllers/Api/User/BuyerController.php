<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UpdateBuyerProfileRequest;
use App\Http\Requests\StoreBuyerRequest;
use App\Http\Requests\UpdateBuyerRequest;
use App\Models\Buyer;
use App\Models\Service;

class BuyerController extends Controller
{
    public function updateProfile(UpdateBuyerProfileRequest $request)
    {
        $data = $request->validated();

        $user = auth()->user();

        if (!$user->buyer)
            abort(403);

        $user->update($data);
        $user->load("buyer");
        $user->buyer->update($data);

        return response()->json([
            "data" => $user
        ]);
    }
}
