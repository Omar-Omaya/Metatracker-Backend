<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\History;
use App\Models\Department;

use Illuminate\Http\Request;
use Carbon\Carbon;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return History::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /* check */

    public function store(Request $request)
    {

        $content = $request->all();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://worldtimeapi.org/api/timezone/Africa/Cairo');
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        
        $response = curl_exec($ch);
        $response = json_decode($response, true);
        $current_time = Carbon::parse($response['datetime']);
        $current_time= $current_time->format('H:i');

        $content['Start_time'] = $current_time; 

        if(!History::where('employee_id', $request->employee_id )->whereDate('created_at', '=', Carbon::today())->exists()){
         return History::create($content);
            // return $test;
        }
        else{
            History::where('employee_id',$request->employee_id)->whereDate('created_at', '=', Carbon::today())->delete();
            History::create($content);
            return response([ "History exists previous row deleted"], 201);
            // return gettype($test);

            // return $test;
        }
        // $start = History::select($Start_time)

        // if($start)
    }

    public function getAbsenceDay($id)
    {

        return History::where('is_absence','=',true)->where('employee_id',$id)->count();
    }

    public function getAbsenceToday()
    {

        return History::where('is_absence','=',true)->whereDate('created_at',Carbon::today())->count();
    }

    public function countAttendanceDay($id)
    {
        return History::where('is_absence','=',false)->where('employee_id',$id)->count();
    }

    public function getAttendanceToday()
    {
        return History::where('is_absence','=',false)->whereDate('created_at',Carbon::today())->count();
    }

    public function checkInToday(Request $request)
    {
        $location = History::with('Employee')->where('is_absence','=',false)->whereDate('created_at',Carbon::today())->get();

        return $location;
    }

    public function getOutOfZoneToday(){
        return History::where('Out_of_zone', true)->where('is_absence','=',false)->whereDate('created_at',Carbon::today())->get();
    }

    public function getInOfZoneToday(){
        return History::where('Out_of_zone', false)->where('is_absence','=',false)->whereDate('created_at',Carbon::today())->get();
    }

    public function totalHour($id){
        $histories = History::where('employee_id',$id)->get();
        $absence = History::where('is_absence','=',true)->where('employee_id',$id)->count();
        $total = 0;
        foreach($histories as $history){
            $start= $history->created_at;
            $end= $history->updated_at;
            $diff= $start->diff($end)->format('%H');
            $total += $diff;

        }
        $response = [
            'totalHour' => $total,
            'absenceDay' => $absence
        ];

        return response($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return History::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $content = $request->all();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://worldtimeapi.org/api/timezone/Africa/Cairo');
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        
        $response = curl_exec($ch);
        $response = json_decode($response, true);
        $current_time = Carbon::parse($response['datetime']);
        $current_time= $current_time->format('H:i');   

        $content['End_time'] = $current_time; 

        $history = History::where('employee_id',$id)->get()->last();

        $history->update($content);

        return $content;



        // if($history->Out_of_zone==true){
        //     return response([ "Employee is in zone"], 201);
        // }else{
        //     return response([ "Employee is out of zone"], 401);
        // }
    }

    public function destroy($id)
    {
        return History::destroy($id);
    }

    public function getCurrentLocation(Request $request,$id){
        $history = History::where('employee_id',$id)->whereDate('created_at',Carbon::today())->first();
        return $history;
    }
}
