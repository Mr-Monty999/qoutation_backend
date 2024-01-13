<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Models\Country;

class CityController extends Controller
{
    public function getCountryCities(Country $country)
    {

        $cities = $country->cities;
        return response()->json([
            "data" => $cities
        ]);
    }
}
