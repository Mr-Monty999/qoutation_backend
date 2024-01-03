<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = "notifications";
    protected $guarded = [];
    protected $casts = [
        "data" => "json"
    ];


    public function getCreatedAtAttribute($value)
    {
        return [
            'date' => $value,
            'human_readable' => Carbon::parse($value)->diffForHumans(),
        ];
    }
}
