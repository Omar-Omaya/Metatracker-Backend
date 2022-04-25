<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'overhead_name',
        'overhead_role',
        'msg_text',
        'employee_id'
        
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
