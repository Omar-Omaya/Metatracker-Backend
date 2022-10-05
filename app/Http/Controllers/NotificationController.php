<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification as NotificationsNotification;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{

    public static function notification( $mobile_token,$title , $body ,$history_id){
        
        $id= -1;
        if($history_id > 0)
            $id= NotificationController::store($history_id,$body);

        $SERVER_API_KEY = 'AAAA8o82R9Y:APA91bEcTVT3LDwhIQfiCaPEjAzBnXjZLC75-OGAKxmBt2UZAs2RhvAmqBcPRIDmqaxuIu2_RaKNgvArviKasMPAyWxZJChpRPzvlRvOI63lshiezuYcxyDQNMdbglfnqpSuEX4wwcWH';
        // $tokens =Employee::select('mobile_token')->get();
            $data = [
                "registration_ids" => [
                    $mobile_token
                ],
                    "notification" => [
                    "title" => $title,
                    "body" => $body,
                    "id" => $id,
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
          
            // Log::info("Response is ".$response);
            curl_close($ch);
    
            
          
            return $response;
        }

        private static function store($history_id,$body){

            $notification =  Notification::create([
                'history_id' => $history_id,
                'message' => $body
            ]);

            return $notification->id;

        }

        public function addResponse(Request $request){
            $history_id=History::where('employee_id',$request->employee_id)->latest()->first()->id;

            // echo $history_id; 
            DB::table('notifications')
            ->where('history_id', $history_id)->whereNull('reply')->limit(1)
            ->update(['reply' => $request->response,
                        'updated_at', Carbon::now()]);         
        }
    

        

        public function getNotification(Request $request,$id){
            $history = DB::table('notifications')
            ->join('histories','histories.id','=','notifications.history_id')
            ->whereNull('histories.End_time')
            ->where('histories.employee_id', $id)
            ->select('notifications.*')
            ->get();

            return $history;

        }
      
}
