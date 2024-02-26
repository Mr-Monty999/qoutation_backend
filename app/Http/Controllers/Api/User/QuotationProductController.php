<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\QuotationProduct;
use Illuminate\Http\Request;

class QuotationProductController extends Controller
{
    public function show(Request $request, Quotation $quotation, QuotationProduct $quotationProduct)
    {
        if ($quotation->id != $quotationProduct->quotation_id)
            return response()->json([], 403);

        $repliesPerPage = 10;

        if ($request->repliesPerPage)
            $repliesPerPage = $request->repliesPerPage;


        $quotationProduct->load([
            "product",
        ])
            ->loadCount(["replies"]);

        $quotationProduct["replies"] = $quotationProduct->replies()
            ->with("user.supplier", "user.buyer")
            ->where("unit_price", ">", 0)
            ->orderBy("unit_price")
            ->paginate($repliesPerPage, ["*"], "repliesPage");

        return response()->json([
            "data" => $quotationProduct
        ]);
    }
}
