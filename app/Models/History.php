<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'Start_time',
        'End_time',
        'Out_of_zone',
        'lat',
        'lng'
        
    ];

    /**
     * Get the employee that owns the comment.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
