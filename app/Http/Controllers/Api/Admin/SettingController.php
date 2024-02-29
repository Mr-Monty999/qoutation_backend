<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\UpdateSettingRequest as AdminUpdateSettingRequest;
use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
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
            $settings = $settings->get();

        return response()->json([
            "data" => $settings
        ]);
    }
    public function update(AdminUpdateSettingRequest $request)
    {
        $data = $request->validated();

        foreach ($data as $key => $value) {
            if ($value) {
                $setting =  Setting::where("key", $key)->firstOrNew();
                $setting->key = $key;
                $setting->value = $value;
                $setting->save();
            }
        }

        return response()->json([
            "data" => [
                "message" => trans("messages.settings updated successfully"),
            ]
        ]);
    }
}
