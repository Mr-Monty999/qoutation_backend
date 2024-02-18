<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {

        $suppliers = Supplier::with(
            "user.phone",
            "user.country",
            "user.city",
            "user.neighbourhood",
            "user.wallet"
        );
        $perPage = 10;

        if ($request->perPage)
            $perPage = $request->perPage;

        if ($request->has("paginated") && $request->paginated == "true")
            $suppliers = $suppliers->paginate($perPage);
        else
            $suppliers = $suppliers->get();

        return response()->json($suppliers);
    }
}
