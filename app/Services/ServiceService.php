<?php

namespace App\Services;

use App\Models\Service;

/**
 * Class ServiceService.
 */
class ServiceService
{

    public static function store($data)
    {

        $service = Service::create($data);

        return $service;
    }
}
