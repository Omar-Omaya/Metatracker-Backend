<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }
    
    public function admins()
    {
        return $this->hasMany(Admin::class);
    }
}
