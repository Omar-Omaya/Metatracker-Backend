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


            'company_id' => 'required',
            'name' => 'required|string',
            'email' => 'required|string|unique:admins,email',
            'password' => 'required|string',
            'phone' => ' required|string',
            'is_Admin' =>'required|boolean',
            'is_Analyst' => 'boolean',
            'is_HR' =>'boolean',
            'is_IT' => 'boolean'

        ]);


        $admin = Admin::create([

            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'phone' => $fields['phone'],
            'is_Admin' => $fields['is_Admin'],
            'is_Analyst' => $fields['is_Analyst'],
            'is_IT' => $fields['is_IT'],
            'is_HR' => $fields['is_HR'],
            'company_id' => $fields['company_id'],


        ]);

        $token = $admin->createToken('myapptoken')->plainTextToken;
        $token= substr($token , -40,40);
        Admin::where('id', $admin->id)->update(['api_admin_token' => $token]);


        $response = [
            'admin' => $admin,
            'token' => $token
        ];

        return response($response, 201);

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




    public function index()
    {

    }

    public function store(Request $request)
    {
        //
    }

    public function readAllAdmins(Admin $admin)
    {
        return Admin::get();
    }

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
