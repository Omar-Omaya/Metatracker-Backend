<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absence;
use App\Models\Employee;
use App\Models\History;
use App\Models\Department;
use App\Models\Holiday;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;
use Salman\GeoFence\Service\GeoFenceCalculator;
use Illuminate\Support\Facades\DB;


class ManageShift extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manage:shift';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */

    private function getCurrentTime(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://worldtimeapi.org/api/timezone/Africa/Cairo');
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        $response = json_decode($response, true);
        $current_time = Carbon::parse($response['datetime']);
        return $current_time;
    }

    private function updatePaidEmployee(int $id){
        $emp = Employee::where('id', $id)->first();
        $emp->paid++;
        $emp->update();
    }

    public function manageShiftStart(){
        $current_time= $this->getCurrentTime();
        $timeInMinutes= $current_time->format('i');
        $timeInHour= $current_time->format('H');

        // if($timeInMinutes == 00){
            $nameToday = strtolower($current_time->format('l'));
            
            $empofdepartments = DB::table('departments')
            ->join('employees','employees.department_id', '=' ,'departments.id')
            ->join('week_ends','employees.weekend_id', '=','week_ends.id' )
            ->select('employees.*','employees.id as empID','week_ends.'.$nameToday." as day")
            ->where('const_Arrival_time',13)
            ->get();

            $today="20".$current_time->format('y-m-d');
            Log::info($empofdepartments);
            foreach($empofdepartments as $empofdepartment){
                if($empofdepartment->day == 1 ){
                    $this->updatePaidEmployee($empofdepartment->empID);
                    continue;
                }

                $holidays= Holiday::where('employee_id',$empofdepartment->empID )->orWhere('employee_id', null)->where('Day',$today)->get();
                if(! $holidays -> isEmpty()){
                    foreach($holidays as $holiday){
                        if($holiday->Is_paid == 1){
                            $this->updatePaidEmployee($empofdepartment->empID);
                            break;
                        }
                    }
                    continue;
                    }
                    
                    Absence::create([
                        'employee_id'=> $empofdepartment->empID,
                        'Day'=> $today,
                        'pending'=> 1

                    ]);  
                } 
            }
    // }

    public function manageShiftEnd(){
        $current_time= $this->getCurrentTime();
        $timeInMinutes= $current_time->format('i');
        $timeInHour= $current_time->format('H');

        // if($timeInMinutes == 0){
            $departmentabsents = DB::table('departments')
            ->join('employees','employees.department_id', '=' ,'departments.id')
            ->join('absences','employees.id', '=','absences.employee_id' )
            ->select('absences.*')
            ->where('const_Leave_time',12)
            ->where('pending',1)
            ->get();

            foreach($departmentabsents as $departmentabsent){
                $absent = Absence::where('id',$departmentabsent->id)->first();
                $absent->pending = 0;
                $absent->update();
             
            }
        }

    // }


    public function handle()
    {
         // $this->time();
        $this->manageShiftStart();
        $this->manageShiftEnd();
    }
}
