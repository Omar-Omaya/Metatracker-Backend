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
        // $department =  Department::create($request->all());
        $department = $request->all();
        $dp=
        "
        CREATE EVENT AddEventDep".$request->id."
        ON SCHEDULE
        EVERY 1 DAY
        STARTS '2014-04-30 ".$request->startTime."' ON completion PRESERVE ENABLE
        DO
        INSERT INTO absences(employee_id,pending) SELECT 1,true FROM employees;
";
        
    return DB::statement($dp);

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
