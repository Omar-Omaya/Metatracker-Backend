<?php

namespace App\Http\Controllers;
use App\Models\Employee;

use Dirape\Token\Token;
use Laravel\Sanctum\PersonalAccessToken;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    // use HasApiTokens;
    // use HasApiTokens;
    public function register(Request $request) {
        $fields = $request->validate([

            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string',
            'path_image' => 'required|string',
            'phone' => ' required|integer',
            'gender' =>'required|string',
            'Arrival_time' =>'required|integer',
            'Leave_time' =>'required|integer',
            'absence_day' =>'required|integer',
            'position' =>'required|string'
            
        ]);
       

        $user = Employee::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'path_image' => $fields['path_image'],
            'phone' => $fields['phone'],
            'gender' => $fields['gender'],
            'Arrival_time' => $fields['Arrival_time'],
            'Leave_time' => $fields['Leave_time'],
            'absence_day' => $fields['absence_day'],
            'position' => $fields['position'],

        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;
        $token= substr($token , -40,40);
        Employee::where('id', $user->id)->update(['api_token' => $token]);
    

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);

        // return response(['feilds addded'], 201);
    }

    public function login(Request $request){

        $fields = $request->validate([
            'email' =>'required|string',
            'password' =>'required|string',
          
        ]);

        // Check email and Check password
        $user = Employee::where('email', $fields['email'])->first();
        $token = $user->createToken('myapptoken')->plainTextToken;
        $token= substr($token , -40,40);
        Employee::where('id', $user->id)->update(['api_token'=>$token]);
        $user = Employee::where('email', $fields['email'])->first();

        // $response = [
        //     'user' => $user,
        //     'token' => $token
        // ];

        if(!$user||Hash::check($fields['password'], $user->password)) {
            $response = [
                'user' => $user,
                'token' => $token
            ];
            return response()->json($response);
        }else{
            $response = [
                "message" => "invalid email or password"

            ];
            return response($response,401);
        }


        

    //     if(Hash::check($fields['password'], $user->password)) {

    //         return response()->json(['message'=> Employee::where('email', $fields['email'],200)->first(), 
    //     ]);
    // }else{
    //     return response([
    //         'error' => 'Invalid email or password'
    //     ], 401);
    // }
}
}

// if(Hash::check($fields['password'], $user->password)) {
            
//             if($order){
                
//             return response()->json(['message'=> $order,
//         ]);
//             }
//             else{
//                 $test = $request->bearerToken();
//                 $condition = User::where("api_token", $test)->where("id", $user->id)->first();
                
//             // return response()->json(['message'=> $condition]);
//             return response()->json(['message'=> "order not found"]);
            
//             }        
//     }else{
//         return response([
//             'error' => 'Invalid email or password'
//         ], 401);
//     }
