<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\StoreActivityRequest;
use App\Http\Requests\Api\Admin\UpdateActivityRequest;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $activities = Activity::withCount("users", "quotations");

        $perPage = 10;


        if ($request->has("paginated") && $request->paginated == "true")
            $activities = $activities->paginate($perPage);
        else
            $activities = $activities->get();

        return response()->json([
            "data" => $activities
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreActivityRequest $request)
    {
        $data = $request->validated();


        if ($request->hasFile("image")) {
            $fileName = time() . '-' . $request->file("image")->getClientOriginalName();

            $data["image"] = $request->file("image")->storeAs("images/activities", $fileName, "public");
        }

        $activity = Activity::create($data);


        return response()->json([
            "data" => $activity
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Activity $activity)
    {
        return response()->json([
            "data" => $activity
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        $data = $request->validated();

        if ($request->hasFile("image")) {
            $fileName = time() . '-' . $request->file("image")->getClientOriginalName();

            $data["image"] = $request->file("image")->storeAs("images/activities", $fileName, "public");

            if ($activity->image)
                Storage::disk("public")->delete($activity->image);
        }

        $activity->update($data);

        return response()->json([
            "data" => $activity
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activity $activity)
    {

        if ($activity->image)
            Storage::disk("public")->delete($activity->image);

        $activity->delete();

        return response()->json([
            "data" => [
                "message" => trans("messages.activity deleted successfully"),
            ]
        ], 200);
    }
}
