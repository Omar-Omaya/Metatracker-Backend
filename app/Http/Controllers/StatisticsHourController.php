<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\History;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class StatisticsHourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function getDiffHours($start , $end){
         $start = new Carbon('2018-05-12 '.$start.':00');
         $end = new Carbon('2018-05-12 '.$end.':00');

         return $start->diff($end)->format('%H');

     }

    public function getTotalWorkingHours($id)
    {
        $employee =  Employee::where('id',$id)->first();
        $countPaid = $employee->paid;
        $countRowHistory = History::where('employee_id',$id)->count();
        $countRowAbsence = Absence::where('employee_id',$id)->count();

        // $countRowAbsence = Absence::where('employee_id',$id)->where('pending',true)->count();

        $totalWorkingHours = $countPaid + $countRowHistory + $countRowAbsence;

        $depworkingHour = Department::where('id',$employee->department_id)->first();
        $arrival=$depworkingHour->const_Arrival_time;
        $leave=$depworkingHour->const_Leave_time;
        $leave= $leave<$arrival ? $leave+24 : $leave;
        
            // return $depworkingHour;
        return ($leave-$arrival) * $totalWorkingHours ;
       
    }

    public function getTotalActualHours($id){

        $Historys = History::where('employee_id',$id)->whereNotNull('End_time')->get();

        $sum = 0;
        // $start = array();
        // $end = array();

        
        foreach($Historys as $History){
            $diff= $this->getDiffHours($History->Start_time,$History->End_time);
            // $list_start= array_push($start,$History->Start_time);
            // $History->End_time= $History->End_time<$History->Start_time ? $History->End_time+24 : $History->End_time;
            // $list_end = array_push($end,$History->End_time - $History->Start_time);
            // $sum = $sum + $History->End_time - $History->Start_time;
            $sum = $sum + (int)$diff;

            
        }
        return $sum;
     
        // return $sum;
    }

    public function payroll(Request $request){

        $empOfDepartments = DB::table('departments')
                ->join('employees','employees.department_id', '=' ,'departments.id')
                ->select('departments.*','employees.*','employees.id as employee_id')
                ->get();

                foreach($empOfDepartments as $empOfDepartment){

                        $totalwork= $this->getTotalWorkingHours($empOfDepartment->employee_id);
                        $actualwork = $this->getTotalActualHours($empOfDepartment->employee_id);

                        $Histories= History::where('employee_id',$empOfDepartment->employee_id)->get();
                        $overTime = 0;
                        $delay = 0;
                        

                        $diffConstDep = $empOfDepartment->const_Arrival_time < $empOfDepartment->const_Leave_time ? $empOfDepartment->const_Leave_time - $empOfDepartment->const_Arrival_time: $empOfDepartment->const_Leave_time - $empOfDepartment->const_Arrival_time+24;
                          
                        foreach($Histories as $History){
                                if($History->Start_time > $empOfDepartment->const_Arrival_time ) {
                                        $delay = $delay + ($History->Start_time - $empOfDepartment->const_Arrival_time );
                                }
                                if($History->End_time < $empOfDepartment->const_Leave_time )
                                $delay = $delay- ($History->End_time - $empOfDepartment->const_Leave_time);

                                $diffShift = $History->End_time <$History->Start_time ? $History->End_time+24 - $History->Start_time : $History->End_time - $History->Start_time ;

                                if($diffShift>$diffConstDep){
                                        $overTime+=$diffShift-$diffConstDep;
                                    }
                                }
                                
                                $empOfDepartment->totalwork=$totalwork;
                                $empOfDepartment->actualwork=$actualwork;
                                $empOfDepartment->delay=$delay;
                                $empOfDepartment->overTime=$overTime;
                       
                }

                return $empOfDepartments;
}





    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
