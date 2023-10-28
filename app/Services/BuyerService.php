<?php

namespace App\Services;

use App\Models\Supplier;

/**
 * Class BuyerService.
 */
class BuyerService
{

    public static function getAllPaginated($perPage = 5)
    {
        $suppliers = Supplier::paginate($perPage);

        return $suppliers;
    }
}
