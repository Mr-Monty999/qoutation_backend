<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ["id"];

    protected $casts = [
        'attachments' => 'json',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, "sender_id");
    }
    public function replies()
    {
        return $this->hasMany(Message::class, "message_id");
    }

    public function parentMessage()
    {
        return $this->belongsTo(Message::class, "parent_id");
    }

    public function recipient()
    {
        return $this->hasOne(MessageRecipient::class, "message_id");
    }
}
