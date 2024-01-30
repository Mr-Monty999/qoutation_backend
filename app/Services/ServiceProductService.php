<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ServiceProduct;

/**
 * Class ServiceProductService.
 */
class ServiceProductService
{

    public static function store($serviceProducts, $service)
    {

        foreach ($serviceProducts as $serviceProduct) {
            $serviceProduct["service_id"] = $service->id;
            $product = Product::where("name", "=", $serviceProduct["name"])->first();
            if (!$product) {
                $product = Product::create([
                    "name" => $serviceProduct["name"]
                ]);
            }

            $serviceProduct["product_id"] = $product->id;


            ServiceProduct::create($serviceProduct);
        }
    }
}
