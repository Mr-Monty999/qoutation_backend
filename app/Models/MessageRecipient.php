<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageRecipient extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ["id"];

    public function receiver()
    {
        return $this->belongsTo(User::class, "receiver_id");
    }
    public function message()
    {
        return $this->belongsTo(Message::class, "message_id");
    }
}
