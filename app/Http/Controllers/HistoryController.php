<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\History;
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
    public function store(Request $request)
      {
        // if(!History::where('employee_id', $request->employee_id )->whereDate('created_at', '=', Carbon::today())->exists()){
         return History::create($request->all());
        }
        // else{
            // return response([ "is exist"], 401);
        // }
    // }


        // return  History::where('employee_id',$fields['employee_id'])->get();

        // return History::create($request->all());




        //  $history= History::get();
        //  History::where('employee_id','=', $employee_id)->count();
        // return $history;
        // if($history->employee_id)
        // return History::create($request->all());

    public function getAbsenceDay($id)
    {
        return History::where('is_absence','=',true)->where('employee_id',$id)->count();
    }

    public function countAttendanceDay($id)
    {
        return History::where('is_absence','=',false)->where('employee_id',$id)->count();
    }

    public function getLastLocation(Request $request)
    {
        $location = History::with('Employee')->whereDate('created_at',Carbon::today())->get();
       
        return $location;
    }

    public function getOutOfZone(){
        return History::where('Out_of_zone', true)->whereDate('created_at',Carbon::today())->count();
    }

    public function getInOfZone(){
        return History::where('Out_of_zone', false)->whereDate('created_at',Carbon::today())->count();
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
        $history = History::where('employee_id',$id)->get()->last();
        $history->update($request->all());
        if($history->Out_of_zone==true){
            return response([ "Employee is in zone"], 201);
        }else{
            return response([ "Employee is out of zone"], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return History::destroy($id);
    }
}
