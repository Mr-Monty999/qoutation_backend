<?php

namespace App\Services;

use App\Models\Quotation;

/**
 * Class ServiceService.
 */
class QuotationService
{

    public static function store($data)
    {

        $quotation = Quotation::create($data);

        return $quotation;
    }
}
