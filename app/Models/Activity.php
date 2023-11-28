<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class, "user_activity", "activity_id");
    }


    public function services()
    {
        return $this->belongsToMany(Service::class, "service_activity", "activity_id");
    }
}
