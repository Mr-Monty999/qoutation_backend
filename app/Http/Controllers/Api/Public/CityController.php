<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Models\Country;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function getCountryCities(Request $request, Country $country)
    {

        $cities = null;
        $perPage = 10;

        if ($request->perPage)
            $perPage = $request->perPage;

        if ($request->has("paginated") && $request->paginated == "true")
            $cities = $country->cities()->paginate($perPage);
        else
            $cities = $country->cities;

        return response()->json([
            "data" => $cities
        ]);
    }
}
