<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ["id"];

    public function cities()
    {
        return $this->hasMany(City::class, "country_id");
    }


    public function users()
    {
        return $this->belongsToMany(User::class, "user_activity", "activity_id");
    }
}
