<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\History;
use App\Models\Admin;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



use Illuminate\Http\Request;

class StatsController extends Controller
{
// dashboard

    public function inZoneLate(Request $request){
        
      
        $empofdepofhistories = DB::table('departments')
            ->join('employees','employees.department_id', '=' ,'departments.id')
            ->join('histories','histories.employee_id','=','employees.id')
            ->whereDate('histories.created_at',Carbon::today())
            ->get();
        
            $count= 0;
            foreach($empofdepofhistories as $data){
            // $departmentData= StatisticsHourController::getDepartmentTotalShiftHours($empofdepofhistories);
            // print_r($departmentData);
            $arriveTime= $data->const_Arrival_time.":00";
            
            $arriveTime=StatisticsHourController::formatTimeString($arriveTime);

            $startTime=StatisticsHourController::formatTimeString($data->Start_time);
            
            $arriveEarly=StatisticsHourController::getDiffHours($startTime,$arriveTime);
            $arriveAfter= StatisticsHourController::getDiffHours($arriveTime,$startTime);
            $firstDelay= $arriveEarly > $arriveAfter ? $arriveAfter : 0;    
            // $start_hours= intval(explode(":",$data->Start_time)[0]);
            if($firstDelay != 0)
                $count++;

        }
        return $count;

    }

    public function outZoneNoexcuse(Request $request){
        $absence= Absence::where('pending', 1)->whereDate('created_at',Carbon::today())->get();
        return $absence->count();

    }

    public function outZoneholiday(Request $request){
        $holiday =Holiday::whereDate('created_at',Carbon::today())->get();
        return $holiday->count();

    }

    public function totalHour($id , $month){
        $admin_id =auth('sanctum')->user()->id;
        $adminData = Admin::where('id', $admin_id)->first();

        $histories = History::where('employee_id',$id)->whereMonth('created_at' , $month)->where('company_id', $admin_id->company_id)->get();
        $absence = History::where('is_absence','=',true)->where('employee_id',$id)->where('company_id', $admin_id->company_id)->whereMonth('created_at' , $month)->count();
        $total = 0;
        $days = 0;
        
        foreach($histories as $history){
            $start= $history->created_at;
            $end= $history->updated_at;
            $diff= $start->diff($end)->format('%H');
            $total += $diff;
            if($total>=8){
                $days++;
            }

        }
        $response = [
            'totalDays'.$month => $total,
            'absenceDay'. $month => $absence
        ];

        // return response($response, 201);
        return $response;
    }

    public function calculateYearly($id){
        $array2=[];
        for($i = 1; $i <=12 ;$i++){
            $array1 =$this->totalHour($id, $i);
            array_push($array2, $array1);

        }
        return $array2;
    }

    public function getOutOfZoneMonth($month){
        $company_id =auth('sanctum')->user()->compny_id;
        // $adminData = Admin::where('id', $admin_id)->first();
        $history= DB::table('histories')
        ->join('employees','employees.id','=','histories.employee_id')
        ->where('histories.Out_of_zone_time','>' ,0)
        ->where('employees.company_id','=',$company_id)
        ->whereMonth('histories.created_at' , $month)->count();

        return $history;
        // return History::where('Out_of_zone_time','>' ,0)->whereMonth('created_at' , $month)->count();
    }

    public function calcgetOutOfZoneMonth(){
        $array2 = [];
        for($month=1; $month<=12; $month++){
            $array1= $this->getOutOfZoneMonth($month);
            array_push($array2, $array1);

        }
        return $array2;
    }

    public function getOutOfZoneMonthPerEmp($month,$id){
        return History::where('employee_id',$id)->where('Out_of_zone_time','>' ,0)->whereMonth('created_at' , $month)->count();
    }



    public function calcgetOutOfZoneMonthPerEmp($id){
        $array2 = [];
        for($month=1; $month<=12; $month++){
            $array1= $this->getOutOfZoneMonthPerEmp($month,$id);
            array_push($array2, $array1);

        }
        return $array2;
    }


    public function getInOfZoneMonth($month){
        $company_id =auth('sanctum')->user()->compny_id;

        $history= DB::table('histories')
        ->join('employees','employees.id','=','histories.employee_id')
        ->where('histories.Out_of_zone_time','=' ,0)
        ->where('employees.company_id','=',$company_id)
        ->whereMonth('histories.created_at' , $month)->count();
        // return History::where('Out_of_zone_time','=' ,0)->where('is_absence','=',false)->whereMonth('created_at' , $month)->count();
    }

    public function calcgetInOfZoneMonth(){
        $array2 = [];
        for($month=1; $month<=12; $month++){
            $array1= $this->getInOfZoneMonth($month);
            array_push($array2, $array1);
        }

        return $array2;
    }

    public function getInOfZoneMonthPerEmp($month,$id){
        return History::where('employee_id',$id)->where('Out_of_zone_time','=' ,0)->whereMonth('created_at' , $month)->count();
    }

    public function calcgetInOfZoneMonthPerEmp($id){
        $array2 = [];
        for($month=1; $month<=12; $month++){
            $array1= $this->getInOfZoneMonthPerEmp($month,$id);
            array_push($array2, $array1);

        }
        return $array2;
    }

}
