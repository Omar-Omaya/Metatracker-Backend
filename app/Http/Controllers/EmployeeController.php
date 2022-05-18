<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Hospital;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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

    public function getAbsenceDay($id)
    {
        $employee = Employee::select('absence_day')->where('id',$id)->first();
        return $employee;
        
    }

    // public function stime($id)
    // {
    //     $item = Hospital::select('Start_time');
    //     if($item>2){

    //         $emp= Employee::select('absence_day')->where($employee_id, $id))->get();


    //     }
    // }

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
        $employee->update($request->all());
        return $employee;
    }

    public function mobile_token(Request $request, $id)
    {

        $fields = $request->validate([
            'mobile_token' => 'required|string', 
        ]);

        // $mob_token = Employee::where('id' , $id)->update(['mobile_token',$fields['mobile_token']]);
        $mob_token = Employee::where('id' , $id)->first()->update(array('mobile_token'=>$fields['mobile_token']));
        return $mob_token;
        // $update_token=$mob_token->update([])
        // $mob_token->update($request->all());
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Employee::destroy($id);
    }

    /**
     * Search for a name or email
     *
     * @param  str  $name
     * @return \Illuminate\Http\Response
     */

    public function search($name)
    {
        return Employee::where('name', 'like', '%'.$name.'%')->orWhere('email','like','%'.$name.'%')->get();
    }
}

