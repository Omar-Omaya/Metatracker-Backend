<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Employee;
use App\Models\Admin;
use App\Models\Department;
use App\Models\Announcement;
use App\Models\MessageDepartment;
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
            'admin_id'=> 'required'
        ]);

        $message =    Message::create($request->all());

        return $message;
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

    // add message per Employee 

    public function messageEmployee(Request $request, $id)
    {
        $message = Message::create($request->all());

        $employee = Employee::find([$id]);

        $message->employees()->attach($employee);

        return 'add message employee';
  
    }

    // add message per Department 

    public function messageDepartment(Request $request, $id){

        $message = Message::create($request->all());

        $department = Department::find([$id]);

        $message->departments()->attach($department);

        return 'add message department';

    }

    public function messageAnnouncement(Request $request){

        $message = Message::create($request->all());

        $announc = new Announcement();

        $announc->message_id = $message->id;

        $announc->save();

        // $message->announcements()->attach($announc);

        return 'add message announcmenet';
    }

    public function getMessageEmpMobile(Request $request){

        $id = $request->id;
        

    //    return ($request->getContent());
        // $id = (int)explode("=", $request->getContent())[1] ;
        $msgemp = DB::table('employee_message')
        ->join('messages','messages.id', '=' ,'employee_message.message_id')
        ->join('admins','admins.id','=','messages.admin_id')
        ->where('employee_message.employee_id' ,$id)
        ->select('messages.text','admins.name')

        ->first();

        // $response = [
        //     'message'=>$msgemp
        // ];

        return $msgemp;
    }

   

    public function getMessagesMobile(Request $request){

        $id = $request->id;

        $msgdep = DB::table('department_message')
        ->join('messages','messages.id', '=' ,'department_message.message_id')
        ->join('admins','admins.id','=','messages.admin_id')
        ->where('department_message.department_id', $id)
        ->select('messages.text','admins.name')
        ->get();
        
        // return $msgdep;

        $announc = DB::table('announcements')
        ->join('messages','messages.id', '=' ,'announcements.message_id')
        ->join('admins','admins.id','=','messages.admin_id')
        ->select('admins.name', 'messages.text')
        ->get();

        $response = [
            'msgdep' => $msgdep,
            'announc'=> $announc,
        ];

        return response()->json($response);
    }

    public function getAllMessageEmp(Request $request,$admin_id){

        $msgemp = DB::table('employee_message')
        ->join('messages','messages.id', '=' ,'employee_message.message_id')
        ->where('messages.admin_id','=',$admin_id)
        ->select('messages.*','employee_message.*')
        ->get();

        return $msgemp;      
    }

    public function getAllMessageDep(Request $request,$admin_id){

        $msgdep = DB::table('department_message')
        ->join('messages','messages.id', '=' ,'department_message.message_id')
        ->where('messages.admin_id','=',$admin_id)
        ->select('messages.*','department_message.*')
        ->get();

        return $msgdep;
        
    }

    public function getAllMessageAnnounc(Request $request,$admin_id){

        $announc = DB::table('announcements')
        ->join('messages','messages.id', '=' ,'announcements.message_id')
        ->where('messages.admin_id','=',$admin_id)
        ->select('announcements.*', 'messages.*')
        ->get();

        return $announc;
    }


    
    // public function getMessage(){
    //     // $category = Message::find([3, 4]);
    //     // $product->categories()->attach($category);
    //     $getmessage = MessageEmployee::with('message')->where('employee_id', '=', 1)->get();
        
    //     return $getmessage;
        
    // }
    
    
}
