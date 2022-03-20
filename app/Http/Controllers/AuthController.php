<?php

namespace App\Http\Controllers;
use App\Models\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request) {
        $fields = $request->validate([

            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string',
            'phone' => ' required|integer',
            'gender' =>'required|integer',
            'Arrival_time' =>'required|integer',
            'Leave_time' =>'required|integer',
            'position' =>'required|string'

        ]);

        $user = Employee::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'phone' => $fields['phone'],
            'gender' => $fields['gender'],
            'Arrival_time' => $fields['Arrival_time'],
            'Leave_time' => $fields['Leave_time'],
            'position' => $fields['position'],


        ]);

        return response(['feilds addded'], 201);
    }

    public function login(Request $request){

        $fields = $request->validate([
            'email' =>'required|string',
            // 'email' =>'required|string||unique:users,email',
            'password' =>'required|string',
            // 'password' =>'required|string|confirmed'
        ]);

        // Check email and Check password
        $user = Employee::where('email', $fields['email'])->first();

        if(Hash::check($fields['password'], $user->password)) {

            return response()->json(['message'=> Employee::where('email', $fields['email'],200)->first(), 
        ]);
    }else{
        return response([
            'error' => 'Invalid email or password'
        ], 401);
    }
}
}
