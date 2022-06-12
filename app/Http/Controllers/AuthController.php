<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\Department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request as IlluminateRequest;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // use HasApiTokens;
    use HasApiTokens;
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string',
            'phone' => ' required|integer',
            'department_id'=>'required|integer',
            'position'=>'required|string'

        ]);

        $duplicate = Employee::select('email')->where('email',$fields['email'])->exists();
            if(!$duplicate){


        $user = Employee::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'phone' => $fields['phone'],
            'department_id' => $fields['department_id'],
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
    }

        return response(['this email is already exist'], 200);
    }

    public function excel(Request $request){

        $response = [];

        for($i = 0; $i<count($request['emp']); $i++){
            $data = new IlluminateRequest($request['emp'][$i]);

            $fields = $data->validate([
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string',
                'path_image' => 'string',
                'phone' => ' required|integer',
                'position' => ' required|string',

               
                
                
            ]);
            $duplicate = Employee::select('email')->where('email',$fields['email'])->exists();

            if(!$duplicate){


            $user = Employee::create([
                'name' => $fields['name'],
                'email' => $fields['email'],
                'password' => bcrypt($fields['password']),
                'phone' => $fields['phone'],
                'position' => $fields['position']
               
                
            ]);

            //TODO check whether there is dublication or not

            $token = $user->createToken('myapptoken')->plainTextToken;
            $token= substr($token , -40,40);
            Employee::where('id', $user->id)->update(['api_token' => $token]);

                array_push($response, $data['email']);
            }else{
                $stringAdd = $data['email']." => Email already exist";
                array_push($response , $stringAdd);
            }
        }

        return $response;
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
        // $raw_query = 'SELECT * FROM employees INNER JOIN departments ON employees.department_id = departments.id WHERE employees.email =  ';
        // $user = $raw_query . '' . $fields['email'];
        // $user = DB::select($user);
        // $user = json_decode(json_encode($user));
        // $user = Employee::with('Department')->where('email', $fields['email'])->first();
        // $empofdepartment = DB::table('departments')
        //     ->join('employees', 'employees.id', '=', 'employees.department_id')// joining the contacts table , where user_id and contact_user_id are same
        //     ->select('departments.*', 'employees.*')
        //     ->get();

//  || &&

        if(!$user||Hash::check($fields['password'], $user->password)) {
            if(Employee::where('Is_Here','=',true)->where('id',$user->id)->exists()){
                return response([ "Unauthorized"], 401);
            }
            Employee::where('id', $user->id)->update(['Is_Here' => true]);

            $response = [
                'user' =>$user,
                'token' => $token,
                // 'empofdepartment' =>$empofdepartment
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
