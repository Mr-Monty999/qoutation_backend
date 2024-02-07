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
        return $this->hasMany(QuotationReply::class, "quotation_product_id")->where("unit_price", ">", "0");
    }
    public function acceptedReply()
    {
        return $this->hasOne(QuotationReply::class, "quotation_product_id")->whereNotNull("accepted_by");
    }
    public function bestReply()
    {
        return $this->hasOne(QuotationReply::class, "quotation_product_id")->where("unit_price", ">", "0")->orderBy("unit_price");
    }
}
