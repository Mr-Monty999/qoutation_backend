<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\StoreCountryRequest;
use App\Http\Requests\Api\Admin\UpdateCountryRequest;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $countries = Country::withCount("users", "cities");

        $perPage = 10;


        if ($request->has("paginated") && $request->paginated == "true")
            $countries = $countries->paginate($perPage);
        else
            $countries = $countries->get();

        return response()->json([
            "data" => $countries
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCountryRequest $request)
    {
        $data = $request->validated();

        $country = Country::create($data);


        return response()->json([
            "data" => $country
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        return response()->json([
            "data" => $country
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCountryRequest $request, Country $country)
    {
        $data = $request->validated();

        $country->update($data);

        return response()->json([
            "data" => $country
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {

        $country->delete();

        return response()->json([
            "data" => [
                "message" => trans("messages.country deleted successfully"),
            ]
        ], 200);
    }
}
