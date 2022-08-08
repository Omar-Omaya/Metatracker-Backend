<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Employee;
use App\Models\Admin;
use App\Models\MessageEmployee;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{

    public function index()
    {
        $admin_id =auth('sanctum')->user()->id;
        $adminData = Admin::where('id', $admin_id)->first();
        return Message::where('company_id',$adminData->company_id)->get();
    }

    public function store(Request $request)
    {

        $request->validate([
            'text' =>'required',
        ]);

        return Message::create($request->all());
    }

    public function show($id)
    {
        return Message::find($id);
    }

    public function update(Request $request, $id)
    {
        $Message = Message::where('employee_id',$id)->get()->last();
        $Message->update($request->all());
        return $Message;
    }

    public function destroy($id)
    {
        return Message::destroy($id);
    }

    public function getMessage($id){
        $getmessage = DB::table('messages')
            ->join('messages_employees','messages_employees.employee_id', '=' ,'employees.id')
            ->select('messages_employees.*','employees.*')
            ->get();
            return $getmessage;
       
    }


}
