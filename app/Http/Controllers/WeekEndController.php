<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WeekEnd;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WeekEndController extends Controller
{
    public function store(Request $request)
    {
         return WeekEnd::create($request->all());
    }

    public function setHolidayToEmployee(Request $request){
        $holiday_id=WeekEndController::index($request);
        $employee = Employee::find($request->empID);
        $employee->weekend_id=$holiday_id;
        $employee->update();
    }

    public function index(Request $request){
        $week = WeekEnd::select('id')->where($request->day1,1)->where($request->day2,1)->get();

        if($week-> isEmpty() ){
        $week = WeekEnd::create([
            $request->day1 => 1,
            $request->day2 => 1,               
            ])->id ;
        }

        else{
            $week= $week[0]->id;
        }
        return $week;
    }
}
