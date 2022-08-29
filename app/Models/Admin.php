<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Admin extends Model
{
    use HasFactory;
    use HasApiTokens;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
        'password',
        'is_Admin',
        'is_Analyst',
        'is_HR',
        'is_IT',
        'api_admin_token'

    ];

    protected $hidden = [
        'password',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }
}
