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
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

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

            $uuid =  Uuid::uuid4();
            $data["uuid"] = $uuid;


            $walletTransaction = WalletTransaction::create($data);

            $data = TelrService::checkout($data["amount"], $walletTransaction->number, $uuid, "wallet_recharge");


            DB::commit();
            return response()->json([
                "payment" => $data,
                "transaction" => $walletTransaction
            ], 200);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }
    public function rechargeSuccess(Request $request, $uuid)
    {
        DB::beginTransaction();
        try {
            $transaction = WalletTransaction::where("uuid", $uuid)
                ->firstOrFail();

            if ($transaction->status == "pending") {
                $transaction->update([
                    "status" => "paid"
                ]);

                $wallet = $transaction->wallet;
                $wallet->balance += $transaction->amount;
                $wallet->save();
            }

            DB::commit();
            return response()->json([
                "msg" => "wallet recharged successfully",
            ], 200);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }
    public function rechargeCancelled(Request $request, $uuid)
    {
        DB::beginTransaction();
        try {
            $transaction = WalletTransaction::where("uuid", $uuid)
                ->firstOrFail();

            if ($transaction->status == "pending")
                $transaction->update([
                    "status" => "cancelled"
                ]);

            DB::commit();
            return response()->json([
                "msg" => "wallet recharge cancelled",
            ], 200);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }
    public function rechargeDeclined(Request $request, $uuid)
    {
        DB::beginTransaction();
        try {
            $transaction = WalletTransaction::where("uuid", $uuid)
                ->firstOrFail();


            if ($transaction->status == "pending")
                $transaction->update([
                    "status" => "declined"
                ]);

            DB::commit();
            return response()->json([
                "msg" => "wallet recharge declined",
            ], 200);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }
}
