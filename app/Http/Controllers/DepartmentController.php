<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Model\department;
// use App\Model\Employee;
use App\Models\Department;




class DepartmentController extends Controller
{

    public function store(Request $request)
    {
         return Department::create($request->all());
    }

    public function readAllDepartment(Request $request){

         return Department::get();
    }

    public function countAllDepartment(Request $request){
        return Department::count();
    }

    public function getAllDepartment(){
        return Department::get();
    }



}
