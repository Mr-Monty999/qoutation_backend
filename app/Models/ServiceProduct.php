<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ["id"];

    public function service()
    {
        return $this->belongsTo(Service::class, "service_id");
    }
    public function product()
    {
        return $this->belongsTo(Product::class, "product_id");
    }
    public function quotations()
    {
        return $this->hasMany(ProductQuotation::class, "service_product_id");
    }
}
