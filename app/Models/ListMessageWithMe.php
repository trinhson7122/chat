<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListMessageWithMe extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_message',
        'last_user_id_send',
        'from_user_id',
        'to_user_id',
        'to_group_id',
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function toGroup()
    {
        return $this->belongsTo(GroupMessage::class, 'to_group_id');
    }
}
