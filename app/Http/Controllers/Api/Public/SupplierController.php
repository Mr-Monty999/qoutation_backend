<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {

        $suppliers = null;
        $perPage = 10;

        if ($request->perPage)
            $perPage = $request->perPage;

        $suppliers = Supplier::with("user.city", "user.neighbourhood");

        if ($request->order && $request->order == "latest")
            $suppliers =  $suppliers->latest();


        if ($request->has("paginated") && $request->paginated == "true")
            $suppliers = $suppliers->paginate($perPage);
        else
            $suppliers = $suppliers->get();

        return response()->json([
            "data" => $suppliers
        ]);
    }
}
