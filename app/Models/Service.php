<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ["id"];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
    public function activities()
    {
        return $this->belongsToMany(Activity::class, "service_activity", "service_id");
    }

    public function getCreatedAtAttribute($value)
    {
        return [
            'date' => $value,
            'human_readable' => Carbon::parse($value)->diffForHumans(),
        ];
    }

    public function country()
    {
        return $this->belongsTo(Country::class, "country_id");
    }
    public function city()
    {
        return $this->belongsTo(City::class, "city_id");
    }
    public function neighbourhood()
    {
        return $this->belongsTo(Neighbourhood::class, "neighbourhood_id");
    }

    public function replies()
    {
        return $this->hasMany(ServiceReply::class, "service_id");
    }
    public function authUserReply()
    {
        return $this->hasOne(ServiceReply::class, "service_id")->where("user_id", auth()->id());
    }
}
