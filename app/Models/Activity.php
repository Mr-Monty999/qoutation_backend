<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ["id"];

    public function users()
    {
        return $this->belongsToMany(User::class, "user_activity", "activity_id");
    }


    public function quotations()
    {
        return $this->belongsToMany(Quotation::class, "quotation_activity", "activity_id");
    }
}
