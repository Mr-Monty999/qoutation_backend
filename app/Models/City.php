<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ["id"];

    public function country()
    {
        return $this->belongsTo(Country::class, "country_id");
    }
    public function neighbourhoods()
    {
        return $this->hasMany(Neighbourhood::class, "city_id");
    }
}
