<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'day',
        'comment',
        
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

}

    
