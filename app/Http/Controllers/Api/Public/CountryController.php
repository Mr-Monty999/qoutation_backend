<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Models\Country;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::get();

        return response()->json($countries);
    }
}
