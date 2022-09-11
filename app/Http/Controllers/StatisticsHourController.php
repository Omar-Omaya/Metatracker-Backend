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

use function PHPUnit\Framework\isEmpty;

class StatisticsHourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    private function formatTimeString($time)
    {
        $time = explode(":", $time);
        if(count($time) ==1)
            $time[1]=0;
        // echo $time[1];
        $time= [
            'hour' => (int)$time[0],
            'min' => (int)$time[1]
        ];
        // print_r($time);
        return $time;
    }

    private function getDiffHours($start, $end)
    {
        if($end['hour']> $start['hour'])
                      $end = new Carbon('2018-05-12 ' . $end['hour'] . ':' . $end['min'] . ':00');
              else
                      $end = new Carbon('2018-05-13 ' . $end['hour'] . ':' . $end['min'] . ':00');
        $start = new Carbon('2018-05-12 ' . $start['hour'] . ':' . $start['min'] . ':00');
              
              return $start->diff($end)->format('%H');;
    }

    public function getTotalWorkingHours($id)
    {
        $employee =  Employee::where('id', $id)->first();
        $countPaid = $employee->paid;
        $countRowHistory = History::where('employee_id', $id)->count();
        $countRowAbsence = Absence::where('employee_id', $id)->count();

        // $countRowAbsence = Absence::where('employee_id',$id)->where('pending',true)->count();

        $totalWorkingHours = $countPaid + $countRowHistory + $countRowAbsence;

        $depworkingHour = Department::where('id', $employee->department_id)->first();
        $arrival = $depworkingHour->const_Arrival_time;
        $leave = $depworkingHour->const_Leave_time;
        $leave = $leave < $arrival ? $leave + 24 : $leave;

        // return $depworkingHour;
        return ($leave - $arrival) * $totalWorkingHours;
    }

    public function getTotalActualHours($id)
    {  
        $Historys = History::where('employee_id', $id)->whereNotNull('End_time')->get();

        $sum = 0;

        // if(isEmpty($Historys))
        //     return 0;
        // $start = array();
        // $end = array();


        foreach ($Historys as $History) {

            $start_time = $this->formatTimeString($History->Start_time);        

            $end_time = $this->formatTimeString($History->End_time);          

            $diff = $this->getDiffHours($start_time, $end_time);
            $sum = $sum + (int)$diff;
        }
        return $sum;

        // return $sum;
    }
    

    public function payroll(Request $request,$company_id)
    {

        $empOfDepartments = $empOfDepartments = DB::table('departments')

        ->join('employees', 'employees.department_id', '=', 'departments.id')
        ->where('employees.company_id','=', $company_id)
        ->select('departments.*', 'employees.*', 'employees.id as employee_id')
        ->get();

        foreach ($empOfDepartments as $empOfDepartment) {

            $totalwork = $this->getTotalWorkingHours($empOfDepartment->employee_id);
            $actualwork = $this->getTotalActualHours($empOfDepartment->employee_id);

            $Histories = History::where('employee_id', $empOfDepartment->employee_id)->whereNotNull('End_time')->get();
            // $absence = Absence::get();
            $absence = Absence::where('employee_id',$empOfDepartment->employee_id)->where('pending' ,0)->get();
            $countabsence= $absence->count();

            $attendance = History::where('employee_id', $empOfDepartment->employee_id)->whereNotNull('Start_time')->get();
            $countattend= $attendance->count();

            $overTime = 0;

            $delay = 0;


            $diffConstDep = $empOfDepartment->const_Arrival_time < $empOfDepartment->const_Leave_time ? $empOfDepartment->const_Leave_time - $empOfDepartment->const_Arrival_time : $empOfDepartment->const_Leave_time - $empOfDepartment->const_Arrival_time + 24;
            $dep_time_arrival=[
                'hour' =>$empOfDepartment->const_Arrival_time,
                'min'=>0
            ];
            $dep_time_leave=[
                'hour' =>$empOfDepartment->const_Leave_time,
                'min'=>0
            ];
            $diffShift= $this->getDiffHours($dep_time_arrival,$dep_time_leave);

            foreach ($Histories as $History) {

                $start_time = $this->formatTimeString($History->Start_time);
                $end_time = $this->formatTimeString($History->End_time);
                $firstDelay= $start_time['hour']> $dep_time_arrival['hour'] ? $this->getDiffHours($dep_time_arrival,$start_time): 0;
                $secondDelay= $end_time['hour'] < $dep_time_leave['hour'] ? $this->getDiffHours($end_time,$dep_time_leave): 0;
                $delay += ($firstDelay> 0 ? $firstDelay: 0) + ($secondDelay> 0 ? $secondDelay: 0)  ;

                // if ($start_time['hour'] > $empOfDepartment->const_Arrival_time) {
                //     $delay = $delay + ($start_time['hour'] - $empOfDepartment->const_Arrival_time);
                // }
                // if ($end_time['hour'] < $empOfDepartment->const_Leave_time)
                //     $delay = $delay - ($end_time['hour'] - $empOfDepartment->const_Leave_time);

                // $diffShift = $end_time['hour'] < $start_time['hour'] ? $end_time['hour'] + 24 - $start_time['hour'] : $end_time['hour'] - $start_time['hour'];
                $totalShiftHours= $this->getDiffHours($start_time,$end_time);

                if ($totalShiftHours> $diffShift) {
                    $overTime += $totalShiftHours - $diffShift;
                }
            }

            $empOfDepartment->totalwork = $totalwork;
            $empOfDepartment->actualwork = $actualwork;
            $empOfDepartment->delay = $delay;
            $empOfDepartment->overTime = $overTime;
            $empOfDepartment->countabsence = $countabsence;
            $empOfDepartment->countattend = $countattend;



            
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
