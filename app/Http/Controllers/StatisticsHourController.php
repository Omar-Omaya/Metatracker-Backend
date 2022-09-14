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
        // echo  $start->diff($end)->format('%H')."/n"; 
        // echo   $start->diff($end)->format('%i') / 60.0;   
        return $start->diff($end)->format('%H') + $start->diff($end)->format('%i') / 60.0 ;
    }

    public function getTotalWorkingHours($id)
    {
        $employee =  Employee::where('id', $id)->first();

        $countPaid = $employee->paid;
        $countRowHistory = History::where('employee_id', $id)->count();
        $countRowAbsence = Absence::where('employee_id', $id)->count();

        $totalWorkingHours = $countPaid + $countRowHistory + $countRowAbsence;

        $depworkingHour = Department::where('id', $employee->department_id)->first();
        return $this->getDepartmentTotalShiftHours($depworkingHour)['shift_duration'] * $totalWorkingHours;
    }

    public function getTotalActualHours($id)
    {  
        $Historys = History::where('employee_id', $id)->whereNotNull('End_time')->get();

        $sum = 0;

        foreach ($Historys as $History) {

            $start_time = $this->formatTimeString($History->Start_time);        

            $end_time = $this->formatTimeString($History->End_time);          

            $diff = $this->getDiffHours($start_time, $end_time);
            $sum = $sum + $diff;
        }
        return $sum;

        // return $sum;
    }
    

    public function payroll(Request $request,$company_id)
    {

        $empOfDepartments = DB::table('departments')

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


            // $diffConstDep = $empOfDepartment->const_Arrival_time < $empOfDepartment->const_Leave_time ? $empOfDepartment->const_Leave_time - $empOfDepartment->const_Arrival_time : $empOfDepartment->const_Leave_time - $empOfDepartment->const_Arrival_time + 24;
            $departmentTimeData= $this->getDepartmentTotalShiftHours($empOfDepartment);

            foreach ($Histories as $History) {

                $start_time = $this->formatTimeString($History->Start_time);
                $end_time = $this->formatTimeString($History->End_time);
                
                $delay += $this->calculateDelay($departmentTimeData ,$start_time, $end_time) ;
                
                $totalShiftHours= $this->getDiffHours($start_time,$end_time);

                if ($totalShiftHours> $departmentTimeData['shift_duration']) {
                    $overTime += $totalShiftHours - $departmentTimeData['shift_duration'];
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

    private function calculateDelay($departmentTimeData, $start_time, $end_time){
        $firstDelay= min($this->getDiffHours($departmentTimeData['arrive_time'],$start_time),$this->getDiffHours($start_time,$departmentTimeData['arrive_time']));
        $secondDelay= min($this->getDiffHours($end_time,$departmentTimeData['leave_time']),$this->getDiffHours($departmentTimeData['leave_time'],$end_time)) ;
        return $firstDelay+ $secondDelay;       
}


    private function getDepartmentTotalShiftHours($department){
        $dep_time_arrival=[
            'hour' =>$department->const_Arrival_time,
            'min'=>0
        ];
        $dep_time_leave=[
            'hour' =>$department->const_Leave_time,
            'min'=>0
        ];
        $diffShift= $this->getDiffHours($dep_time_arrival,$dep_time_leave);

        return [
            'arrive_time' => $dep_time_arrival,
            'leave_time' => $dep_time_leave,
            'shift_duration' =>$diffShift
        ];
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
