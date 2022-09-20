<?php

namespace App\Console\Commands;

use App\Models\Absence;
use App\Models\Employee;
use App\Models\History;
use App\Models\Department;
use App\Models\User;
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
                            // $distance = $d_calculator->CalculateDistance($department->lat, $department->lng, $historiesOfEmployee->lat, $historiesOfEmployee->lng);
                            if($historiesOfEmployee['Out_of_zone'] ==1){
                                // History::where('employee_id', $historiesOfEmployee->employee_id)->update(array('Out_of_zone' => true ,'Out_of_zone_time' => Carbon::now()->toDateTimeString()));
                                $this->notification($historiesOfEmployee->Employee->mobile_token, 'zoneStatus' , 'You are out of zone !');
                                // Log::info("Out of zone");
                            }else{
                                // History::where('employee_id', $historiesOfEmployee->employee_id)->update(['Out_of_zone' => false]);
                                // $this->notification($historiesOfEmployee->Employee->mobile_token, 'Notification' , 'Any problem ?');
                                $message = explode("|",$department->message);
                                $this->notification($historiesOfEmployee->Employee->mobile_token, $message[0] , $message[1]);
                                // Log::info("In zone");
                            }
                        }
                    }
                }
            }
        }else{
            Log::info("Empty Array");
            
        }
    }

    public function lateNotify(){

        $absentEmployees = DB::table('absences')
        ->join('employees','employees.id' ,'=','absences.employee_id')
        ->where('absences.pending','=',1)
        ->get();

        foreach($absentEmployees as $absentEmployee){
            $this->notification($absentEmployee->mobile_token,'Late','You are late');

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

        
    public function notification( $mobile_token,$title , $body){

    $SERVER_API_KEY = 'AAAA8o82R9Y:APA91bEcTVT3LDwhIQfiCaPEjAzBnXjZLC75-OGAKxmBt2UZAs2RhvAmqBcPRIDmqaxuIu2_RaKNgvArviKasMPAyWxZJChpRPzvlRvOI63lshiezuYcxyDQNMdbglfnqpSuEX4wwcWH';
    // $tokens =Employee::select('mobile_token')->get();
        $data = [
            "registration_ids" => [$mobile_token],
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
      
        Log::info("Response is ".$response);
        curl_close($ch);
      
        return $response;
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

           