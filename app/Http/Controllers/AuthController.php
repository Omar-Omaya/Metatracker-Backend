<?php

namespace App\Http\Controllers;
use App\Models\Employee;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){

        $fields = $request->validate([
            'email' =>'required|string',
            // 'email' =>'required|string||unique:users,email',
            'password' =>'required|string',
            // 'password' =>'required|string|confirmed'
        ]);

        // Check email
        $user = Employee::where('email', $fields['email'])->where('password', $fields['password'])->first();

        // Check password
        if($user != null) {
            return response()->json(['message'=> Employee::where('email', $fields['email'],200)->first(), 
        ]);
    }else{
        return response([
            'error' => 'Invalid email or password'
        ], 401);
    }
}
}
