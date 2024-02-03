<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationQuotation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ["id"];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function acceptedBy()
    {
        return $this->belongsTo(User::class, "accepted_by");
    }
    public function quotation()
    {
        return $this->belongsTo(Quotation::class, "quotation_id");
    }
}
