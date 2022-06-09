<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\History;
use App\Models\Department;
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
                        $distance = $d_calculator->CalculateDistance($department->lat, $department->lng, $historiesOfEmployee->lat, $historiesOfEmployee->lng);
                        // if(is_null($historiesOfEmployee->End_time)){
                            if($distance > 1000){

                                History::where('employee_id', $historiesOfEmployee->employee_id)->update(array('Out_of_zone' => true ,'Out_of_zone_time' => Carbon::now()->toDateTimeString()));
                                $this->notification($historiesOfEmployee->Employee->mobile_token, 'Warning' , 'Your are currently out of zone');
                                Log::info("Out of zone");
                            }else{
                                History::where('employee_id', $historiesOfEmployee->employee_id)->update(['Out_of_zone' => false]);
                                $this->notification($historiesOfEmployee->Employee->mobile_token, 'Notification' , 'Any problem ?');
                                Log::info("In zone");
                            }
                    // }
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

        Log::info($response);
    }


    public function handle()
    {
        // $this->notification($token, "test", "test");
        $this->distance();

    }
}

