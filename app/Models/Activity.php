<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }


    public function services()
    {
        return $this->belongsToMany(Service::class, "service_service_type", "service_type_id");
    }
}
