<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Employee;
use App\Models\History;
use App\Models\MonthlyProductivity;
use Illuminate\Http\Request;

class MonthlyProductivityController extends Controller
{
    public function transferSalary(Request $request){

        $employee=Employee::find($request->employee_id);
        $employee->paid = 0;
        $employee->update();

        // $count_history = History::where('employee_id',$request->employee_id)->whereMonthly()->count();  

        $history= History::where('employee_id',$request->employee_id)->whereNotNull('End_time')->delete();

        $absence= Absence::where('employee_id',$request->employee_id)->where('pending', 0)->delete();
        return MonthlyProductivity::create($request->all());

    }

    public function getTransferSalary(Request $request){

        return MonthlyProductivity::get();


    }

}
