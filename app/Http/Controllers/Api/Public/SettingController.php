<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request)
    {

        $settings = Setting::with([]);
        $perPage = 10;

        if ($request->perPage)
            $perPage = $request->perPage;


        if ($request->has("paginated") && $request->paginated == "true")
            $settings = $settings->paginate($perPage);
        else
            $settings = $settings->pluck("value", "key");

        return response()->json([
            "data" => $settings
        ]);
    }
}
