<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'company_id',
        'admin_id',
        'dep_id',
        'employee_id',
        'text'
        
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
