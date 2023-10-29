<?php

namespace App\Models;

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

    public function serviceQoutations()
    {
        return $this->hasMany(ServiceQoutation::class, "service_id");
    }
    public function serviceTypes()
    {
        return $this->belongsToMany(ServiceType::class, "service_service_type", "service_id");
    }
}
