<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductQuotation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ["id"];

    public function serviceProduct()
    {
        return $this->belongsTo(ServiceProduct::class, "service_product_id");
    }
    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
