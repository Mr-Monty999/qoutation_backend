<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $admins = Admin::with(["user"]);

        $perPage = 10;

        if ($request->perPage)
            $perPage = $request->perPage;


        if ($request->has("paginated") && $request->paginated == "true")
            $admins = $admins->paginate($perPage);
        else
            $admins = $admins->get();

        return response()->json([
            "data" => $admins
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            "name" => "required|string",
            "email" => "required|email|unique:users,email",
            // "phone" => "required|numeric",
            "password" => "required|string|min:8",
            "password_confirmation" => "required|string|same:password"
        ]);

        $data["password"] = Hash::make($data["password"]);



        if ($request->hasFile("image")) {
            $fileName = time() . '-' . $request->file("image")->getClientOriginalName();

            $data["image"] = $request->file("image")->storeAs("images/admins", $fileName, "public");
        }

        $user = User::create($data);
        $data["user_id"] = $user->id;
        $admin = Admin::create($data);
        $admin["user"] = $user;

        return response()->json([
            "data" => $admin
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        $admin->load("user");
        return response()->json([
            "data" => $admin
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        $data = $this->validate($request, [
            "name" => "required|string",
            "email" => "required|email|unique:users,email," . $admin->user->id,
            // "phone" => "required|numeric",
            "password" => "nullable|string|min:8",
            "password_confirmation" => "required_with:password|string|same:password"
        ]);

        if ($request->password) {
            $data["password"] = Hash::make($data["password"]);
        }

        if ($request->hasFile("image")) {
            $fileName = time() . '-' . $request->file("image")->getClientOriginalName();

            $data["image"] = $request->file("image")->storeAs("images/admins", $fileName, "public");

            if ($admin->image)
                Storage::disk("public")->delete($admin->image);
        }

        $admin->load("user");
        $admin->user->update($data);
        $admin->update($data);

        return response()->json([
            "data" => $admin
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {

        if ($admin->image)
            Storage::disk("public")->delete($admin->image);

        $admin->user->delete();
        $admin->delete();

        return response()->json([
            "data" => [
                "message" => trans("messages.admin deleted successfully"),
            ]
        ], 200);
    }
}
