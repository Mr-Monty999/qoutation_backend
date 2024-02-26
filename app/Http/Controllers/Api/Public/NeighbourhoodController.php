<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Neighbourhood;
use App\Http\Requests\StoreNeighbourhoodRequest;
use App\Http\Requests\UpdateNeighbourhoodRequest;
use App\Models\City;

class NeighbourhoodController extends Controller
{

    public function getCityNeighbourhoods(City $city)
    {

        $neighbourhoods = $city->neighbourhoods;

        return response()->json([
            "data" => $neighbourhoods
        ]);
    }
}
