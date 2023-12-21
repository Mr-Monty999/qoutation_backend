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

    public function serviceQuotations()
    {
        return $this->hasMany(ServiceQuotation::class, "service_id");
    }
    public function userQuotation()
    {
        return $this->hasOne(ServiceQuotation::class, "service_id")->where("user_id", auth()->id());
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
}
