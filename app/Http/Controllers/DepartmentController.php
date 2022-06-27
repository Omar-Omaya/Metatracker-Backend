<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Model\department;
// use App\Model\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\DB;





class DepartmentController extends Controller
{

    public function store(Request $request)
    {
         return Department::create($request->all());
    }

    
    public function countAllDepartment(Request $request){
        return Department::count();
    }

    public function getAllDepartment(){
        return Department::get();
    }

    public function empOfDepartments(){
    $empofdepartment = DB::table('departments')
            ->join('employees','employees.department_id', '=' ,'departments.id')
            ->select('departments.*','employees.*')
            ->get();

            return $empofdepartment;

        
    }  



}
