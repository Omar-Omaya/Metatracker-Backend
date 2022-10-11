<?php

namespace App\Console\Commands;

use App\Models\History;
use App\Models\Department;
use App\Models\Notification;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Salman\GeoFence\Service\GeoFenceCalculator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StatisticsHourController;

class LogCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:cron';

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
     * @return mixed
     */

    public function distance()
    {
          $d_calculator = new GeoFenceCalculator();
          $departments = Department::get();
          $historiesOfEmployees = History::with('Employee')->whereDate('created_at',Carbon::today())->get();
          $current_time= Carbon::now()->format('H:i');
          $current_time= StatisticsHourController::formatTimeString($current_time);
          if(!$historiesOfEmployees->isEmpty() ){
          foreach($departments as $department){
            $dep_data= StatisticsHourController::getDepartmentTotalShiftHours($department); // Get shift duration here.
              foreach($historiesOfEmployees as $historiesOfEmployee){
                  if($department->id == $historiesOfEmployee->Employee->department_id){
                        if(is_null($historiesOfEmployee->End_time)){
                            $this->manageWorkingNotification($dep_data,$department,$current_time,$historiesOfEmployee);
                        }
                    }
                }
            }
        }else{
            Log::info("Empty Array");
            
        }
    }


    private function manageWorkingNotification($dep_data,$department,$current_time,$historiesOfEmployee){
        $start_time= StatisticsHourController::formatTimeString($historiesOfEmployee['Start_time']);
        $time_passed= StatisticsHourController::getDiffHours($start_time,$current_time);
        if($time_passed > $dep_data['shift_duration']){
            if(!Notification::isLastResponseAdded($historiesOfEmployee['id'])){
                $last_response_time= Notification::getLastResponseTime($historiesOfEmployee['id']);
                $last_response_time= new Carbon($last_response_time);
                $last_response_time= $last_response_time->format('H:i');
                History::forceCheckout($historiesOfEmployee['id'],$last_response_time);
                //Force Checkout.
                return;
            }
        }
        if($historiesOfEmployee['Out_of_zone'] ==1){
            NotificationController::notification($historiesOfEmployee->Employee->mobile_token, 'zoneStatus' , 'You are out of zone !', -1);
        }else{
            $message = explode("|",$department->message);
            NotificationController::notification($historiesOfEmployee->Employee->mobile_token, $message[0] , $message[1] , $historiesOfEmployee->id);
        }

    }

    public function lateNotify(){

        $absentEmployees = DB::table('absences')
        ->join('employees','employees.id' ,'=','absences.employee_id')
        ->where('absences.pending','=',1)
        ->get();

        foreach($absentEmployees as $absentEmployee){
            NotificationController::notification($absentEmployee->mobile_token,'Late','You are late',-1);
        }
    }
    
    public function time(){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://worldtimeapi.org/api/timezone/Africa/Cairo');
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        

        $response = curl_exec($ch);
        $response = json_decode($response, true);
        $current_time = Carbon::parse($response['datetime']);
        // $current_time= $current_time->format('H:i');
        // Log::info($current_time);

        $dep= Department::first();
        $const_Leave_time = $dep->const_Leave_time . ":00";
        $const_Leave_time = Carbon::parse($const_Leave_time);

        // Log::info($current_time);

        
        // $operation = $const_Leave_time->diffInHours($current_time);
        $operation = $current_time->diffInHours($const_Leave_time, false);
        $time = $const_Leave_time->format('H:i');
        // Log::info($operation);

        $histories= History::whereDate('created_at', '=', Carbon::today())->get();
        foreach($histories as $history){
            if(History::whereNull('End_time')->where('id',$history->id)->where('Out_of_zone', true)->exists()
                && $operation <= 0 
            ){
                $update= History::where('id', $history->id)->update(array('End_time' => $time));

                Log::info($update);
            }
            Log::info($const_Leave_time." - ".$current_time . "=" . $operation);

        }

    }                                                                                                                                                                                                                                                                            

        
    

    public function handle()
    {
    // $mobile_token =Employee::select('mobile_token')->get();

        $this->lateNotify();
        $this->distance();
        // $this->time();
        // $this->manageShiftStart();
        // $this->manageShiftEnd();


    }
}

           