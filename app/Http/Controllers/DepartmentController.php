<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;





class DepartmentController extends Controller
{

    public function store(Request $request)
    {
        $department =  Department::create($request->all());
       
    //     DB::statement("
    //     DROP EVENT  AddEventDep".$department->id.";
    //     CREATE EVENT AddEventDep".$department->id."
    //     ON SCHEDULE
    //     EVERY 1 DAY
    //     STARTS '2014-04-30 ".$request->const_Arrival_time.":15:40' ON completion PRESERVE ENABLE
    //     DO
    //     INSERT INTO absences(employee_id,Day,pending,created_at,updated_at) SELECT id,CURDATE(),true,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP FROM employees where department_id = ".$department->id.";
    // ");
    //     DB::statement("
    //     drop event SetPendFalse".$department->id.";
    //     CREATE EVENT SetPendFalse".$department->id."
    //     ON SCHEDULE
    //     EVERY 1 DAY
    //     STARTS '2014-04-30 ".$request->const_Leave_time.":00:00' ON completion PRESERVE ENABLE
    //     DO
    //     UPDATE absences SET pending=false, updated_at=CURRENT_TIMESTAMP WHERE employee_id IN (SELECT id FROM employees WHERE department_id= ".$department->id.");
    //     ");

    return "success";



        // $admin_id =auth('sanctum')->user()->id;
        // $adminData = Admin::where('id', $admin_id)->first();
        // return Department::create($request->all());

    }


    public function countAllDepartment(Request $request){

        $admin_id =auth('sanctum')->user()->id;
        $adminData = Admin::where('id', $admin_id)->first();
        return Department::where('company_id',$adminData->company_id)->where('company_id',$adminData->company_id)->count();
    }

    public function readAllDepartment(){

        $admin_id =auth('sanctum')->user()->id;
        $adminData = Admin::where('id', $admin_id)->first();
        return Department::where('company_id',$adminData->company_id)->get();
    }

    public function empOfDepartments(){
    $empofdepartment = DB::table('departments')
            ->join('employees','employees.department_id', '=' ,'departments.id')
            ->select('departments.*','employees.*')
            ->get();
            return $empofdepartment;

    }



}
