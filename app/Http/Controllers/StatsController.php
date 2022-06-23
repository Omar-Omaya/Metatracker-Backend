<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\History;

use Illuminate\Http\Request;

class StatsController extends Controller
{

    public function totalHour($id , $month){
        $histories = History::where('employee_id',$id)->whereMonth('created_at' , $month)->get();
        $absence = History::where('is_absence','=',true)->where('employee_id',$id)->whereMonth('created_at' , $month)->count();
        $total = 0;
        foreach($histories as $history){
            $start= $history->created_at;
            $end= $history->updated_at;
            $diff= $start->diff($end)->format('%H');
            $total += $diff;

        }
        $response = [
            'totalHour'.$month => $total,
            'absenceDay'. $month => $absence
        ];

        // return response($response, 201);
        return $response;
    }

    public function calculateYearly($id){
        $array2=[];
        for($i = 0; $i <=12 ;$i++){
            $array1 =$this->totalHour($id, $i);
            $array2=array_push($array1, $array2);
            


            

        }
    }
    
}
