<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Employee extends Model
{
    use HasFactory;
    use HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'path_image',
        'phone',
        'gender',
        'Arrival_time',
        'Leave_time',
        'absence_day',
        'position',
        'lat',
        'lng',
        'api_token'
        
    ];

      /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    
    protected $hidden = [
        'password',
        

    ];
    
    /**
     * Get the histories for the blog post.
     */
    public function histories()
    {
        return $this->hasMany(History::class);
    }
}
