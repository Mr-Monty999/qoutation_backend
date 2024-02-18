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

        if ($request->type && $request->type == "unaccepted")
            $suppliers->whereNull("accepted_at");

        if ($request->type && $request->type == "accepted")
            $suppliers->whereNotNull("accepted_at");

        if ($request->has("paginated") && $request->paginated == "true")
            $suppliers = $suppliers->paginate($perPage);
        else
            $suppliers = $suppliers->get();

        return response()->json([
            "data" => $suppliers
        ]);
    }

    public function accept(Request $request, Supplier $supplier)
    {
        $supplier->accepted_at = now();
        $supplier->save();

        return response()->json([
            "data" => [
                "message" => trans("messages.supplier accepted successfully"),
            ]
        ]);
    }
}
