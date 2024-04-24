<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $packages = Package::with([]);

        $perPage = 10;

        if ($request->perPage) {
            $perPage = $request->perPage;
        }

        $packages->orderBy("price");

        if ($request->has("paginated") && $request->paginated == "true") {
            $packages = $packages->paginate($perPage);
        } else {
            $packages = $packages->get();
        }

        return response()->json([
            "data" => $packages
        ]);
    }
}
