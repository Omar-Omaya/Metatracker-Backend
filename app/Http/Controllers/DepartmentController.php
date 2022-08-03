<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Model\department;
// use App\Model\Employee;
use App\Models\Department;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;





class DepartmentController extends Controller
{

    public function store(Request $request)
    {
        $admin_id =auth('sanctum')->user()->id;
        $adminData = Admin::where('id', $admin_id)->first();
        return Department::create($request->all());
    }


    public function countAllDepartment(Request $request){

        $admin_id =auth('sanctum')->user()->id;
        $adminData = Admin::where('id', $admin_id)->first();
        return Department::where('company_id',$adminData->company_id)->count();
    }

    public function readAllDepartment(){

        $admin_id =auth('sanctum')->user()->id;
        $adminData = Admin::where('id', $admin_id)->first();
        return Department::where('company_id',$adminData->company_id)->get();
    }

    //TODO Alaa
    public function empOfDepartments(){
    $empofdepartment = DB::table('departments')
            ->join('employees','employees.department_id', '=' ,'departments.id')
            ->select('departments.*','employees.*')
            ->get();
            return $empofdepartment;

    }



}
