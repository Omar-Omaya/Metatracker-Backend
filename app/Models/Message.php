<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'company_id',
        'admin_id',
        'text'
        // 'department_id',
        // 'employee_id',
        
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
        
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // public function messagesemployees()
    // {
    //     return $this->belongsToMany(MessageEmployee::class);
    // }

}
