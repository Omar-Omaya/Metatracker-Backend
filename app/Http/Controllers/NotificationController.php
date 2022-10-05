<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

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
                "id" => $id,
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
      
}
