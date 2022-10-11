<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'employee_id',
        'Start_time',
        'End_time',
        'Out_of_zone',
        'Out_of_zone_time',
        'is_absence',
        'lat',
        'lng',
      
    ];

    /**
     * Get the employee that owns the comment.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public static function forceCheckout($id, $end_time){
        $record=History::find($id);
        $record->End_time= $end_time;
        $record->update();
    }
}
