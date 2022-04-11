<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $counter = DB::select("SELECT COUNT(id) FROM employees");
        
        // return (Employee::all() , compact($counter));
        $list = [
            "Counter" => $counter, 
            "Data" => Employee::all()
        ];
        return $list;
        
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

