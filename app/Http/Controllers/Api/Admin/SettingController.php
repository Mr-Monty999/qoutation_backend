<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\UpdateSettingRequest as AdminUpdateSettingRequest;
use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $untranslateableKeys = [
            "supplier_quotation_price",
            "supplier_wallet_signup_gift",
            "website_logo",
            "home_page_feature_1_icon",
            "home_page_feature_2_icon",
            "home_page_feature_3_icon",
            "home_page_feature_4_icon",
            "contact_page_image_1",
        ];

        foreach ($data as $key => $value) {
            if ($value) {
                $setting =  Setting::where("key", $key)->firstOrNew();
                $setting->key = $key;

                if ($request->hasFile($key)) {
                    $fileName = time() . '-' . $request->file($key)->getClientOriginalName();

                    $value = $request->file($key)->storeAs("images/settings", $fileName, "public");

                    if ($setting->value)
                        Storage::disk("public")->delete($setting->value);
                }

                if (in_array($key, $untranslateableKeys))
                    $value = [
                        "en" => $value,
                        "ar" => $value
                    ];

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
