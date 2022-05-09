<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'D_name',
        'const_Arrival_time',
        'const_Leave_time',
        'lat',
        'lng',
          
    ];

    public function employee()
    {
        return $this->hasMany(Employee::class);
    }
}
