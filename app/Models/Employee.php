<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Model\department;



class Employee extends Model
{
    use HasFactory;
    use HasApiTokens;
    use Notifiable;

    protected $guarded = [];

    protected $fillable = [
        'company_id',
        'department_id',
        'name',
        'email',
        'password',
        'path_image',
        'phone',
        'position',
        'api_token',
        'Is_Here',
        'mobile_token'
    ];

      /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    
    protected $hidden = [
        'password',
    ];
    
    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }

    public function messages()
    {
        return $this->belongsToMany(Message::class);
    }


    public function company()
    {
        return $this->belongsTo(Company::class);
    }



    


    

    
}
