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
        $start = new Carbon('2018-05-12 ' . $start['hour'] . ':' . $start['min'] . ':00');
        $end = new Carbon('2018-05-12 ' . $end['hour'] . ':' . $end['min'] . ':00');
        return $start->diff($end)->format('%H');
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

    public function payroll(Request $request)
    {

        $empOfDepartments = DB::table('departments')

            ->join('employees', 'employees.department_id', '=', 'departments.id')
            ->select('departments.*', 'employees.*', 'employees.id as employee_id')
            ->get();

        foreach ($empOfDepartments as $empOfDepartment) {

            $totalwork = $this->getTotalWorkingHours($empOfDepartment->employee_id);
            $actualwork = $this->getTotalActualHours($empOfDepartment->employee_id);

            $Histories = History::where('employee_id', $empOfDepartment->employee_id)->whereNotNull('End_time')->get();
            // $absence = Absence::get();
            $absence = Absence::where('employee_id',$empOfDepartment->employee_id)->where('pending' ,0)->get();
            $conutabsence= $absence->count();

            $overTime = 0;

            $delay = 0;


            $diffConstDep = $empOfDepartment->const_Arrival_time < $empOfDepartment->const_Leave_time ? $empOfDepartment->const_Leave_time - $empOfDepartment->const_Arrival_time : $empOfDepartment->const_Leave_time - $empOfDepartment->const_Arrival_time + 24;

            foreach ($Histories as $History) {

                $start_time = $this->formatTimeString($History->Start_time);
                $end_time = $this->formatTimeString($History->End_time);


                if ($start_time['hour'] > $empOfDepartment->const_Arrival_time) {
                    $delay = $delay + ($start_time['hour'] - $empOfDepartment->const_Arrival_time);
                }
                if ($end_time['hour'] < $empOfDepartment->const_Leave_time)
                    $delay = $delay - ($end_time['hour'] - $empOfDepartment->const_Leave_time);

                $diffShift = $end_time['hour'] < $start_time['hour'] ? $end_time['hour'] + 24 - $start_time['hour'] : $end_time['hour'] - $start_time['hour'];

                if ($diffShift > $diffConstDep) {
                    $overTime += $diffShift - $diffConstDep;
                }
            }

            $empOfDepartment->totalwork = $totalwork;
            $empOfDepartment->actualwork = $actualwork;
            $empOfDepartment->delay = $delay;
            $empOfDepartment->overTime = $overTime;
            $empOfDepartment->conutabsence = $conutabsence;

            
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
