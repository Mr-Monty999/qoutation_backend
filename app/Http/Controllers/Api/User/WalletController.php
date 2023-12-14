<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\WalletRechargeRequest;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\TelrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function recharge(WalletRechargeRequest $request)
    {

        DB::beginTransaction();
        try {
            $data = $request->validated();
            $user = auth()->user();
            $data["user_id"] = $user->id;
            $data["wallet_id"] = $user->wallet->id;
            $data["number"] = time();


            $walletTransaction = WalletTransaction::create($data);

            $data = TelrService::checkout($data["amount"], $walletTransaction->number);


            DB::commit();
            return response()->json([
                "msg" => "recharged successfully",
            ], 200);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }
    public function rechargeSuccess(Request $request)
    {
    }
    public function rechargeCancelled(Request $request)
    {
    }
    public function rechargeDeclined(Request $request)
    {
    }
}
