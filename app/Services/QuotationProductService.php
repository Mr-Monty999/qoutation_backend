<?php

namespace App\Services;

use App\Models\Product;
use App\Models\QuotationProduct;

/**
 * Class QuotationProductService.
 */
class QuotationProductService
{

    public static function store($quotationProducts, $quotation)
    {

        foreach ($quotationProducts as $quotationProduct) {
            $quotationProduct["quotation_id"] = $quotation->id;
            $product = Product::where("name", "=", $quotationProduct["name"])->first();
            if (!$product) {
                $product = Product::create([
                    "name" => $quotationProduct["name"]
                ]);
            }

            $quotationProduct["product_id"] = $product->id;


            QuotationProduct::create($quotationProduct);
        }
    }
}
