<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\StatisticsHourController;

use App\Models\Absence;
use App\Models\Employee;
use App\Models\History;
use App\Models\Department;
use Salman\GeoFence\Service\GeoFenceCalculator;
use Illuminate\Support\Facades\DB;


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

    private function getCurrentTime(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://timeapi.io/api/TimeZone/zone?timeZone=Africa/Cairo');
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $response = curl_exec($ch);
        $response = json_decode($response, true);
        $current_time = Carbon::parse($response['currentLocalTime']);
        return $current_time;
}

    public function store(Request $request)
    {

        $fields = $request->validate([
            'employee_id' => 'required',
            'lat' => 'required',
            'lng' => 'required'
        ]);


        $content = $request->all();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://timeapi.io/api/TimeZone/zone?timeZone=Africa/Cairo');
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $response = curl_exec($ch);
        $response = json_decode($response, true);
        $current_time = Carbon::parse($response['currentLocalTime']);
        $current_time= $current_time->format('H:i');


        $content['Start_time'] = $current_time;

        // $employee_id =auth('sanctum')->user()->id;

        $employee_id= $fields['employee_id'];
        

        if(!History::where('employee_id', $request->employee_id )->whereDate('created_at', '=', Carbon::today())->exists()){
        //  return History::create($content);
        $is_inzone = $this->distance($request->employee_id,$request->lat,$request->lng);

        if($is_inzone){

            History::create([
                'employee_id' => $fields['employee_id'],
                'Start_time' => $current_time,
                'Out_of_zone' => 0,
                'lat' => $fields['lat'],
                'lng' => $fields['lng']
    
            ]);
        
        }
        return ["is_created" =>$is_inzone ? true: false];

        }
        else{
            // History::where('employee_id',$request->employee_id)->whereDate('created_at', '=', Carbon::today())->delete();
            // History::create($content);
            return response([ "History exists previous row deleted"], 402);
        }

        // if($history->Out_of_zone==true){
        //     return response([ "Employee is in zone"], 201);
        // }else{
        //     return response([ "Employee is out of zone"], 401);
        // }

    }

    public function getAbsenceDay(Request $request)
    {
        $id = $request->id;
        return ["count" => Absence::where('employee_id',$id)->count()];
    }

    public function getAbsenceToday()
    {
        $employee_id =auth('sanctum')->user()->id;

        return ["count" =>Absence::whereDate('created_at',Carbon::today())->count()];
    }

    public function countAttendanceDay(Request $request)
    {
        $id = $request->id;
      

        return ["count" => History::where('employee_id',$id)->count()];
    }

    public function getAttendanceToday()
    {
        return ["count" => History::whereDate('created_at',Carbon::today())->count()];
    }

    public function checkInToday(Request $request,$company_id)
    {

        $location = DB::table('histories')
        ->join('employees','employees.id', '=','histories.employee_id')
        ->where('employees.company_id',$company_id )
        ->whereDate('histories.created_at',Carbon::today())->get();
        // $location = History::with('Employee')->whereDate('created_at',Carbon::today())->get();

        return $location;
    }

    public function latlngEmp(Request $request,$id)
    {
        $location = History::select('lat','lng','Start_time','End_time')->where('employee_id',$id)->whereDate('created_at',Carbon::today())->get();
        return $location;
    }

    public function getOutOfZoneToday(Request $request,$company_id){

        return DB::table('histories')
        ->join('employees','employees.id', '=','histories.employee_id')
        ->where('employees.company_id',$company_id )
        ->where('Out_of_zone', true)
        ->whereDate('histories.created_at',Carbon::today())->get();
        // return History::where('Out_of_zone', true)->whereDate('created_at',Carbon::today())->get();
    }

    public function getInOfZoneToday(Request $request,$company_id){
        
        return DB::table('histories')
        ->join('employees','employees.id', '=','histories.employee_id')
        ->where('employees.company_id',$company_id )
        ->where('Out_of_zone', false)
        ->whereDate('histories.created_at',Carbon::today())->get();
        // return History::where('Out_of_zone', false)->whereDate('created_at',Carbon::today())->get();
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
    public function update(Request $request)
    {
        $fields = $request->validate([
            'employee_id' => 'required',
            'lat' => 'required',
            'lng' => 'required'
        ]);

        $content = $request->all();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://timeapi.io/api/TimeZone/zone?timeZone=Africa/Cairo');
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $response = curl_exec($ch);
        $response = json_decode($response, true);
        $current_time = Carbon::parse($response['currentLocalTime']);
        $current_time= $current_time->format('H:i');

        $content['End_time'] = $current_time;

        $employee_id= $fields['employee_id'];

        
        // $employee_id =auth('sanctum')->user()->id;

        $history = History::where('employee_id',$fields['employee_id'])->get()->last();

        $is_inzone = $this->distance($request->employee_id,$request->lat,$request->lng);


        $history->update([
            'employee_id' => $employee_id,
            'lat' => $fields['lat'],
            'lng' => $fields['lng'],
            'End_time' => $current_time,
            'Out_of_zone' => !$is_inzone,
            'Out_of_zone_time' => $this->compute_out_zone_time($history)
            ]
        );

        return ["is_inzone" =>$is_inzone ? true: false];

    }

    public function updateLatLong(Request $request){
        $fields = $request->validate([
            'employee_id'=>'required',
            'lat' => 'required',
            'lng' => 'required'
        ]);

        // $employee_id =auth('sanctum')->user()->id;
        $employee_id= $fields['employee_id'];

        $history = History::where('employee_id',$fields['employee_id'])->get()->last();

        $is_inzone = $this->distance($request->employee_id,$request->lat,$request->lng);

        $update = $history->update([
            'employee_id' => $employee_id,
            'lat' => $fields['lat'],
            'lng' => $fields['lng'],
            'Out_of_zone' =>!$is_inzone,
            'Out_of_zone_time' => $this->compute_out_zone_time($history)
        ]);



        $response =[
           'update' => $is_inzone ? true: false

        ];

        return response($response,201);
    }

    public function destroy($id)
    {
        return History::destroy($id);
    }

    public function getCurrentLocation(Request $request,$id){
        $history = History::where('employee_id',$id)->whereDate('created_at',Carbon::today())->first();
        return $history;
    }

    private function distance($employee_id, $lat, $lng)
    {
            $d_calculator = new GeoFenceCalculator();
            $department = Department::find(Employee::find($employee_id)->department_id);

            $distance = $d_calculator->CalculateDistance($department->lat, $department->lng, $lat, $lng)*1000;
            return $distance <$department->radius;
    }

    private function compute_out_zone_time(History $history){

        if(!$history->Out_of_zone)
        return $history->Out_of_zone_time;

        $current_time= $this->getCurrentTime();
        $time_diff= $current_time->diffInMinutes($history->updated_at);  
        $outZone=$history->Out_of_zone_time+ $time_diff;

        return $outZone;
}



    private function computeDelay($arriveTime,$startTime){       
        $arriveEarly=StatisticsHourController::getDiffHours($startTime,$arriveTime);
        $arriveAfter= StatisticsHourController::getDiffHours($arriveTime,$startTime);
        $firstDelay= $arriveEarly > $arriveAfter ? $arriveAfter : 0; 
        return $firstDelay;    

     }

        
     public function inZoneLateEmp(Request $request){
        
        $id = $request->id;
    
        $empofdepofhistories = DB::table('departments')
                ->join('employees','employees.department_id', '=' ,'departments.id')
                ->join('histories','histories.employee_id','=','employees.id')
                ->where('employees.id',$id)
                ->whereNull('histories.End_time')
                ->first();
        
                $arriveTime= $empofdepofhistories->const_Arrival_time.":00";               
                $arriveTime= StatisticsHourController::formatTimeString($arriveTime);
                $startTime= StatisticsHourController::formatTimeString($empofdepofhistories->Start_time);
                $delay = (double) $this->computeDelay($arriveTime,$startTime);
                $out_zone_time= $empofdepofhistories->
                ;
                $currentTime= StatisticsHourController::formatTimeString(Carbon::now()->format("H:i"));
                $total_time_till_now=StatisticsHourController::getDiffHours($startTime,$currentTime);
                return [
                        'delay' => $delay,
                        'out_zone_time' => $out_zone_time,
                        'total_time' => $total_time_till_now
                ];
        }

            


}
