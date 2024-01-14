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

        if ($request->has("paginated") && $request->paginated == "true")
            $suppliers = Supplier::with("user")->paginate($perPage);
        else
            $suppliers = Supplier::with("user")->get();

        return response()->json([
            "data" => $suppliers
        ]);
    }
}
