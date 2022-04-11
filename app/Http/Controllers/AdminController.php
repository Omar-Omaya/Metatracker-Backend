<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{

    public function register(Request $request) {
        $fields = $request->validate([

            'name' => 'required|string',
            'email' => 'required|string|unique:admins,email',
            'password' => 'required|string',
            'phone' => ' required|integer',
            'is_Admin' =>'required|string',
            'is_Analyst' => 'string',
            'is_HR' =>'string',
            'is_IT' => 'string'
              
        ]);
       

        $admin = Admin::create([

            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'phone' => $fields['phone'],
            'is_Admin' => $fields['is_Admin'],
            'is_Analyst' => $fields['is_Analyst'],
            'is_HR' => $fields['is_HR'],
            'is_IT' => $fields['is_IT']
            
        ]);

        $token = $admin->createToken('myapptoken')->plainTextToken;
        $token= substr($token , -40,40);
        Admin::where('id', $admin->id)->update(['api_admin_token' => $token]);
    

        $response = [
            'admin' => $admin,
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
        $admin = Admin::where('email', $fields['email'])->first();
        $token = $admin->createToken('myapptoken')->plainTextToken;
        $token= substr($token , -40,40);
        Admin::where('id', $admin->id)->update(['api_admin_token'=>$token]);
        $admin = Admin::where('email', $fields['email'])->first();

        // $response = [
        //     'admin' => $admin,
        //     'token' => $token
        // ];

        if(!$admin||Hash::check($fields['password'], $admin->password)) {
            $response = [
                'admin' => $admin,
                'token' => $token
            ];
            return response()->json($response);
        }else{
            $response = [
                "message" => "invalid email or password"

            ];
            return response($response,401);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
