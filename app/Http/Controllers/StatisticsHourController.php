<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\History;
use Illuminate\Support\Facades\DB;


class StatisticsHourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

        $Historys = History::where('employee_id',$id)->get();

        $sum = 0;
        $start = array();
        $end = array();

        foreach($Historys as $History){
           $list_start= array_push($start,$History->Start_time);
           $History->End_time= $History->End_time<$History->Start_time ? $History->End_time+24 : $History->End_time;
           $list_end = array_push($end,$History->End_time - $History->Start_time);
           $sum = $sum +$History->End_time - $History->Start_time;
       
        }
     
        return $sum;
    }

    public function payroll(Request $request){

        $empofdepartments = DB::table('departments')
            ->join('employees','employees.department_id', '=' ,'departments.id')
            
            ->select('departments.*','employees.*','employees.id as employee_id')->get();

            foreach($empofdepartments as $empofdepartment){

                $totalwork= $this->getTotalWorkingHours($empofdepartment->employee_id);
                $actualwork = $this->getTotalActualHours($empofdepartment->employee_id);

                $Histories= History::where('employee_id',$empofdepartment->employee_id)->get();
                $overTime = 0;
                $delay = 0;
                $array_api = [];

                $diffconstdep = $empofdepartment->const_Arrival_time < $empofdepartment->const_Leave_time ? $empofdepartment->const_Leave_time - $empofdepartment->const_Arrival_time: $empofdepartment->const_Leave_time - $empofdepartment->const_Arrival_time+24;

                foreach($Histories as $History){
                    if($History->Start_time > $empofdepartment->const_Arrival_time ) {
                        $delay = $delay + ($History->Start_time - $empofdepartment->const_Arrival_time );
                    }
                    if($History->End_time < $empofdepartment->const_Leave_time )
                    $delay = $delay- ($History->End_time - $empofdepartment->const_Leave_time);

                    $diffshift = $History->End_time <$History->Start_time ? $History->End_time+24 - $History->Start_time : $History->End_time - $History->Start_time ;
    
                    if($diffshift>$diffconstdep){
                        $overTime+=$diffshift-$diffconstdep;
                    }
                }

                $api = (object)[
                    'employee_id' => $empofdepartment->employee_id,
                    'employee_name' =>  $empofdepartment->name,
                    'dep_name' => $empofdepartment->dep_name,
                    'position' =>$empofdepartment->position,
                    'totalwork' => $totalwork,
                    'actualwork' => $actualwork,
                    'delay' => $delay,
                    'overTime' => $overTime
                    
                ];

                

                $array_api[$empofdepartment->employee_id]=clone $api;


               
            }
            return $array_api;


    }

    public function delayHours(Request $request,$id){

        $Historys = History::where('employee_id',$id)->get();
        // foreach($Historys as $History){
        $empofdepartments = DB::table('departments')
            ->join('employees','employees.department_id', '=' ,'departments.id')
            ->join('histories','histories.employee_id', '=','employees.id' )
            ->select('departments.*','histories.*','employees.*')
            ->where('const_Arrival_time',13)
            
            ->get();



            return $Historys;
    
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
