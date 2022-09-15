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
            'position'=>'required|string',
            'company_id' => 'required',
            'salary' => 'required'

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
            'company_id' => $fields['company_id'],
            'salary' => $fields['salary'],

            
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

    // excel

    public function excel(Request $request){

        $response = [];

        for($i = 0; $i<count($request['emp']); $i++){
            $data = new IlluminateRequest($request['emp'][$i]);

            $fields = $data->validate([
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string',
                'phone' => ' required|integer',
                'department_id'=>'required|integer',
                'position'=>'required|string',
                'company_id' => 'required'
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
                'company_id' => $fields['company_id']

            ]);


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

    public function imageUploadPost(Request $request)
    {
        $request->validate([
            'path_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $imageName = time().'.'.$request->image->extension();
        $request->path_image->move(public_path('images'), $imageName);
    }




    public function login(Request $request){

        $fields = $request->validate([
            'email' =>'required|string',
            'password' =>'required|string',
            'mobile_token' =>'required|string'

        ]);


        $user = Employee::where('email', $fields['email'])->first();
        $token = $user->createToken('myapptoken')->plainTextToken;
        $token= substr($token , -40,40);
        Employee::where('id', $user->id)->update(['api_token'=>$token]);
        $mob_token = Employee::where('id' , $user->id)->first()->update(array('mobile_token'=>$fields['mobile_token']));

        $employee= Employee::where('email',$fields['email'])->first();
        
        $department= Department::where('id',$employee->department_id)->first();
        $object= [
            'employee' =>$employee,
            'department' =>$department
        ];


        // $empofdepartment = DB::table('departments')
        //     ->join('employees','employees.department_id', '=' ,'departments.id')
        //     ->where('email', $fields['email'])
        //     ->select('departments.*', 'employees.department_id','employees.id' ,'employees.name','employees.position','employees.path_image')
        //     ->first();

        if(!$user||Hash::check($fields['password'], $user->password)) {
            if(Employee::where('Is_Here','=',true)->where('id',$user->id)->exists()){
                return response([ "Unauthorized"], 401);
            }
            Employee::where('id', $user->id)->update(['Is_Here' => true]);

            $response= [
                // 'user' =>$user,
                'token' => $token,
                'employee'=> $employee,
                'department'=> $department
                
            ];
            return response()->json($response);
        }else{
            $response = [
                "message" => "invalid email or password"
            ];
            
            return response($response,401);
        }

}
}

