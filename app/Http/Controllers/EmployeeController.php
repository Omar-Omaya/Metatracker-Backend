<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Hospital;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\History;
use App\Models\Department;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Salman\GeoFence\Service\GeoFenceCalculator;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $counter = DB::select("SELECT id FROM employees");
        // $counter = Employee::select('id');

        // return (Employee::all() , compact($counter));
        return $counter;

    }

    public function getAllEmployees(){
        return Employee::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([

            'email' =>'required',
            'password' =>'required'
        ]);

         return Employee::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Employee::find($id);
    }

    // public function


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        $fields= $request->validate([

            'dep_name' =>'required',
        ]);
        if(empty($fields['dep_name'])){
            $department_id = Department::where('dep_name',$fields['dep_name'])->first();
            Employee::where('id',$id)->update(array('department_id'=> $department_id->id));
        }

        $employee->update($request->all());
        return $employee;
    }

    public function mobile_token(Request $request, $id)
    {
        $fields = $request->validate([
            'mobile_token' => 'required|string',
        ]);

        $mob_token = Employee::where('id' , $id)->first()->update(array('mobile_token'=>$fields['mobile_token']));
        return $mob_token;
    }

    public function destroy($id)
    {
        return Employee::destroy($id);
    }


    public function search($name)
    {
        return Employee::where('name', 'like', '%'.$name.'%')->orWhere('email','like','%'.$name.'%')->get();
    }

    public function storeImage(Request $request,$id){
        // Employee::

        if(!$request->hasFile('image')) {
            return response()->json(['upload_file_not_found'], 400);
        }
        $file = $request->file('image');
        if(!$file->isValid()) {
            return response()->json(['invalid_file_upload'], 400);
        }

        $path = public_path() . '/images';
        $file->move($path, $file->getClientOriginalName());
        $imageUrl = $path . "/" .$file->getClientOriginalName();
        Employee::where('id',$id)->update(array('path_image'=> $imageUrl));






        return response()->json($imageUrl);


    }





}

