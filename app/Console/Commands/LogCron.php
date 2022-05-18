<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\History;
use Illuminate\Http\Request;
use Carbon\Carbon;



use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
    
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

    public function check(){

        

        
        $employee = Employee::get();

        foreach($employee as $data){
            if(History::where('employee_id',$data->id)->whereDate('created_at',Carbon::today()->exists())){


            }else{
                $hisrtory = History::create([
                'employee_id' => $data->id,
                'Start_time' => 0,
                'End_time' => 0,
                'Out_of_zone' => 0,
                'lat' => 0,
                'lng' => 0,
                'Out_of_zone_time' => 0,
                
                
                
                
            ]);
            Log::info($hisrtory);

            }
        }

        
        // $histories = History::select('employee_id')->where('employee_id',$fields['employee_id'])->exists();

        // if(!$histories){

        //     $hisrtory = History::create([
        //         'employee_id' => $fields['employee_id'],
        //     ]);
        //     return response($hisrtory);

        // }
    }

        // $histories = History::where('employee_id',$employee_id)->get();
        // foreach($histories as $history){
        //     if(History::where('id', $history->employee_id )->exists() && History::with('Employee')->whereDate('created_at',Carbon::today())->get()){



        //     }

        
        // }
    
    public function handle()
    {
        $employee = Employee::get();

        foreach($employee as $data){
            if(History::where('employee_id',$data->id)->whereDate('created_at',Carbon::today()->exists())){


            }else{
                $hisrtory = History::create([
                'employee_id' => $data->id,
                'Start_time' => 0,
                'End_time' => 0,
                'Out_of_zone' => 0,
                'lat' => 0,
                'lng' => 0,
                'Out_of_zone_time' => 0,
                
                
                
                
            ]);
            Log::info($hisrtory);

            }
        }
        
        // $user = Employee::get();
        // // for(!$user){

        // // }

        // foreach($user as $data1){
        //     $token_1 = $data1->mobile_token;
        //     // Log::info($token_1);
            
        // $SERVER_API_KEY = 'AAAAIcuTN7M:APA91bE7BypbrcpQyq4Quxt8inZF4-yeOcpGQUU5I1cXd_5jEO7t2EfA-jNKUUbZlKarVOWAt5iVjTxM2Fubh85BA6qE3rCZY9Zwx1fmPJK1fza5xKZfpIJpPmEQ7v-10WMiBldCHl7a';

        // // $token_1 = 'd3erNeJxTTOsSoBRI3EHfi:APA91bFQBQjZIk0WOcNxCsepIyKv7U2VEJgL9m9Les32lpkp22dXKiyiEYhUekRkln9PD4-n0vuXEQtpKJB1Y8xl6CmrAMmNvlDYCLAt08gCDZwRsvRuCcKfh0yG-v_Z0likMCbXBk5T';

        // $data = [

        //     "registration_ids" => [
        //         $token_1
        //     ],

        //     "notification" => [

        //         "title" => 'Ezayk 3amla 2a',

        //         "body" => 'el7amdallah',

        //         "sound"=> "default" // required for sound on ios

        //     ],

        // ];

        // $dataString = json_encode($data);

        // $headers = [

        //     'Authorization: key=' . $SERVER_API_KEY,

        //     'Content-Type: application/json',

        // ];

        // $ch = curl_init();

        // curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

        // curl_setopt($ch, CURLOPT_POST, true);

        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        // $response = curl_exec($ch);

        // // return $response;
            
        // // Log::info($response);

        // }





      
        /*
           Write your database logic we bellow:
           Item::create(['name'=>'hello new']);
        */
    }
}

