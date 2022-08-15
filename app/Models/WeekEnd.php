<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeekEnd extends Model
{
    use HasFactory;

    protected $fillable = [
        'saturday',
        'sunday',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday'    
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
