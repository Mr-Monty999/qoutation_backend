<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $packages = Package::with([]);

        $perPage = 10;

        if ($request->perPage)
            $perPage = $request->perPage;


        if ($request->has("paginated") && $request->paginated == "true")
            $packages = $packages->paginate($perPage);
        else
            $packages = $packages->get();

        return response()->json([
            "data" => $packages
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
            "days" => "required|integer",
            "description" => "nullable|string",
            "image" => "nullable|image|max:2048",
            "color" => "nullable|string"
        ]);

        $package = Package::create($data);


        return response()->json([
            "data" => $package
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package)
    {
        return response()->json([
            "data" => $package
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Package $package)
    {
        $data = $this->validate($request, [
            "name" => "required|string",
            "days" => "required|integer",
            "description" => "nullable|string",
            "image" => "nullable|image|max:2048",
            "color" => "nullable|string"
        ]);

        $package->update($data);

        return response()->json([
            "data" => $package
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $package)
    {

        $package->delete();

        return response()->json([
            "data" => [
                "message" => trans("messages.package deleted successfully"),
            ]
        ], 200);
    }
}
