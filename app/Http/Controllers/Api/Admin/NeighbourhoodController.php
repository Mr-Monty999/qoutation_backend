<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\StoreNeighbourhoodRequest;
use App\Http\Requests\Api\Admin\UpdateNeighbourhoodRequest;
use App\Models\Neighbourhood;
use Illuminate\Http\Request;

class NeighbourhoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $neighbourhoods = Neighbourhood::with("city.country")
            ->withCount("users");

        $perPage = 10;

        if ($request->perPage)
            $perPage = $request->perPage;


        if ($request->has("paginated") && $request->paginated == "true")
            $neighbourhoods = $neighbourhoods->paginate($perPage);
        else
            $neighbourhoods = $neighbourhoods->get();

        return response()->json([
            "data" => $neighbourhoods
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNeighbourhoodRequest $request)
    {
        $data = $request->validated();

        $neighbourhood = Neighbourhood::create($data);


        return response()->json([
            "data" => $neighbourhood
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Neighbourhood $neighbourhood)
    {
        return response()->json([
            "data" => $neighbourhood
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNeighbourhoodRequest $request, Neighbourhood $neighbourhood)
    {
        $data = $request->validated();

        $neighbourhood->update($data);

        return response()->json([
            "data" => $neighbourhood
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Neighbourhood $neighbourhood)
    {

        $neighbourhood->delete();

        return response()->json([
            "data" => [
                "message" => trans("messages.neighbourhood deleted successfully"),
            ]
        ], 200);
    }
}
