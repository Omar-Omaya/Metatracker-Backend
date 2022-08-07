<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageEmployee extends Model
{
    use HasFactory;

    protected $table = 'messages_employees';

    protected $guarded = [];

    protected $fillable = [
        'message_id',
        'employee_id'
    ];

    public function messages()
    {
       return $this->belongsToMany(Message::class);
    }

    public function employees()
    {
       return $this->belongsToMany(Employee::class);
    }
}
