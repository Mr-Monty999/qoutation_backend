<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Neighbourhood extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ["id"];

    public function city()
    {
        return $this->belongsTo(City::class, "city_id");
    }
    public function users()
    {
        return $this->hasMany(User::class, "neighbourhood_id");
    }
}
