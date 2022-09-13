<?php

namespace App\Console\Commands;

use App\Models\Absence;
use App\Models\Employee;
use App\Models\History;
use App\Models\Department;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Salman\GeoFence\Service\GeoFenceCalculator;
use Illuminate\Support\Facades\DB;

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

          if(!$historiesOfEmployees->isEmpty() ){
          foreach($departments as $department){
              foreach($historiesOfEmployees as $historiesOfEmployee){
                  if($department->id == $historiesOfEmployee->Employee->department_id){
                        if(is_null($historiesOfEmployee->End_time)){
                            $distance = $d_calculator->CalculateDistance($department->lat, $department->lng, $historiesOfEmployee->lat, $historiesOfEmployee->lng);
                            if($distance > 0){

                                History::where('employee_id', $historiesOfEmployee->employee_id)->update(array('Out_of_zone' => true ,'Out_of_zone_time' => Carbon::now()->toDateTimeString()));
                                $this->notification($historiesOfEmployee->Employee->mobile_token, 'Warning' , 'You are out of zone !');
                                Log::info("Out of zone");
                            }else{
                                History::where('employee_id', $historiesOfEmployee->employee_id)->update(['Out_of_zone' => false]);
                                // $this->notification($historiesOfEmployee->Employee->mobile_token, 'Notification' , 'Any problem ?');
                                $this->notification($historiesOfEmployee->Employee->mobile_token, 'Notification' , $department->message);

                                Log::info("In zone");
                            }
                    }
                  }
              }
          }
        }else{
                Log::info("Empty Array");

        }
    }

    // public function getAbsenceDay($id)
    // {
    //     $employee = Employee::select('absence_day')->where('id',$id)->first();
    //     return $employee;
    // }


        


    public function notification($token_1 , $title , $body){

    $SERVER_API_KEY = 'AAAAIcuTN7M:APA91bE7BypbrcpQyq4Quxt8inZF4-yeOcpGQUU5I1cXd_5jEO7t2EfA-jNKUUbZlKarVOWAt5iVjTxM2Fubh85BA6qE3rCZY9Zwx1fmPJK1fza5xKZfpIJpPmEQ7v-10WMiBldCHl7a';
    $data = [
        "registration_ids" => [
            $token_1
        ],
            "notification" => [
            "title" => $title,
            "body" => $body,
            "sound"=> "default" // required for sound on ios
        ],
    ];

    $dataString = json_encode($data);

    $headers = [

        'Authorization: key=' . $SERVER_API_KEY,

        'Content-Type: application/json',

    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);
    $response = json_decode($response, true);

        Log::info($response);
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

        // $Out_of_zone=$his->Out_of_zone;



    }                                                                                                                                                                                                                                                                            




    public function handle()
    {
        $this->notification($token_1, "test", "test");
        $this->distance();
        // $this->time();
        // $this->manageShiftStart();
        // $this->manageShiftEnd();


    }
}

