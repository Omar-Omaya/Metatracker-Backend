<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Model\department;
// use App\Model\Employee;
use App\Models\department;




class DepartmentController extends Controller
{

    public function store(Request $request)
    {
        // $request->validate([
            
        //     'email' =>'required',
        //     'password' =>'required'
        // ]);

         return department::create($request->all());
        // return 'test';
    }
    //
}
