<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\History;

use Illuminate\Http\Request;

class StatsController extends Controller
{

    public function totalHour($id , $month){
        // $month = 6;
        $histories = History::where('employee_id',$id)->whereMonth('created_at' , $month)->get();
        $absence = History::where('is_absence','=',true)->where('employee_id',$id)->whereMonth('created_at' , $month)->count();
        $total = 0;
        $days = 0;
        foreach($histories as $history){
            $start= $history->created_at;
            $end= $history->updated_at;
            $diff= $start->diff($end)->format('%H');
            $total += $diff;
            if($total>=8){
                $days++;
            }

        }
        $response = [
            'totalDays'.$month => $total,
            'absenceDay'. $month => $absence
        ];

        // return response($response, 201);
        return $response;
    }

    public function calculateYearly($id){
        $array2=[];
        for($i = 1; $i <=12 ;$i++){
            $array1 =$this->totalHour($id, $i);
            array_push($array2, $array1);

        }
        return $array2;
    }

    public function getOutOfZoneMonth($month){
        return History::where('Out_of_zone', true)->where('is_absence','=',false)->whereMonth('created_at' , $month)->count();
    }

    public function calcgetOutOfZoneMonth(){
        $array2 = [];
        for($month=1; $month<=12; $month++){
            $array1= $this->getOutOfZoneMonth($month);
            array_push($array2, $array1);

        }
        return $array2;
    }

    public function getOutOfZoneMonthPerEmp($month,$id){
        return History::where('employee_id',$id)->where('Out_of_zone', true)->where('is_absence','=',false)->whereMonth('created_at' , $month)->count();
    }

    public function calcgetOutOfZoneMonthPerEmp($id){
        $array2 = [];
        for($month=1; $month<=12; $month++){
            $array1= $this->getOutOfZoneMonthPerEmp($month,$id);
            array_push($array2, $array1);

        }
        return $array2;
    }

    
    public function getInOfZoneMonth($month){
        return History::where('Out_of_zone', false)->where('is_absence','=',false)->whereMonth('created_at' , $month)->count();
    }

    public function calcgetInOfZoneMonth(){
        $array2 = [];
        for($month=1; $month<=12; $month++){
            $array1= $this->getInOfZoneMonth($month);
            array_push($array2, $array1);
        }

        return $array2;
    }

    public function getInOfZoneMonthPerEmp($month,$id){
        return History::where('employee_id',$id)->where('Out_of_zone', false)->where('is_absence','=',false)->whereMonth('created_at' , $month)->count();
    }

    public function calcgetInOfZoneMonthPerEmp($id){
        $array2 = [];
        for($month=1; $month<=12; $month++){
            $array1= $this->getOutOfZoneMonthPerEmp($month,$id);
            array_push($array2, $array1);

        }
        return $array2;
    }

}
