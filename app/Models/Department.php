<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Department extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'dep_name',
        'const_Arrival_time',
        'const_Leave_time',
        // 'text',
        'lat',
        'lng',

        

    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
