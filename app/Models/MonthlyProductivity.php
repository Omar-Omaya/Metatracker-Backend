<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyProductivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'total_working_hours',
        'actual_working_hours',
        'delay_hours',
        'overtime_hours',
        'absent_days',
        'salary',
        
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    

}
