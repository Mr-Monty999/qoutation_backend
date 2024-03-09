<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ["id"];

    protected $casts = [
        "data" => "json"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
