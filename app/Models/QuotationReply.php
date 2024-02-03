<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationReply extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ["id"];


    public function quotation()
    {
        return $this->belongsTo(Quotation::class, "quotation_id");
    }
    public function quotationProduct()
    {
        return $this->belongsTo(QuotationProduct::class, "quotation_product_id");
    }
    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
