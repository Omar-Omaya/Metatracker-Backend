<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Model\Employee;

class department extends Model
{
        use HasFactory;
        // use HasApiTokens;
        // use Notifiable;

        protected $fillable = [
        'dep_name',
        'const_Arrival_time',
        'const_Leave_time',
        'Position',
        'lat',
        'lng'
    ];


    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
