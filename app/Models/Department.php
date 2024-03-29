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
        'company_id',
        'dep_name',
        'const_Arrival_time',
        'const_Leave_time',
        'message',
        'color',
        'lat',
        'lng',
        'radius'

    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function messages()
    {
        return $this->belongsToMany(Message::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
