<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageDepartment extends Model
{
    use HasFactory;

    protected $table = 'department_message';

    protected $guarded = [];

    protected $fillable = [
        'message_id',
        'department_id'
    ];
}
