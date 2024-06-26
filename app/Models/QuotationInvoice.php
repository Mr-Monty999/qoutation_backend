<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationInvoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ["id"];

    public function replies()
    {
        return $this->hasMany(QuotationReply::class, "quotation_invoice_id");
    }
}
