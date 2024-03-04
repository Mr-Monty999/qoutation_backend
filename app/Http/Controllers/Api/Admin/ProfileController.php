<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\UpdateAdminProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(UpdateAdminProfileRequest $request)
    {
        $data = $request->validated();


        DB::beginTransaction();
        try {


            $user = auth()->user();

            if (!$user->admin)
                return response()->json([], 403);

            if ($request->hasFile("image")) {
                $fileName = time() . '-' . $request->file("image")->getClientOriginalName();

                $data["image"] = $request->file("image")->storeAs("images/admins", $fileName, "public");

                if ($user->admin->image)
                    Storage::disk("public")->delete($user->admin->image);
            }

            $user->update($data);
            // $user->phone->update([
            //     "number" => $request->phone
            // ]);
            $user->load("admin", "phone");
            $user->admin->update($data);

            DB::commit();
            return response()->json([
                "data" => $user
            ]);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }
}
