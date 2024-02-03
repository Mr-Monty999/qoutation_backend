<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ["id"];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, "quotation_id");
    }
    public function product()
    {
        return $this->belongsTo(Product::class, "product_id");
    }
    public function replies()
    {
        return $this->hasMany(QuotationReply::class, "quotation_product_id");
    }
}
