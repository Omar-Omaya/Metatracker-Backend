<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'gender',
        'Arrival_time',
        'Leave_time',
        'absence_day',
        'position',
        'lat',
        'lng'
    
    ];

    /**
     * Get the histories for the blog post.
     */
    public function histories()
    {
        return $this->hasMany(History::class);
    }
}
