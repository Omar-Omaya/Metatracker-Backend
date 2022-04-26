<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Employee;

class MessageController extends Controller
{

    public function index()
    {
        return Message::all();
    }

    public function store(Request $request)
    {

        $request->validate([
            'msg_text' =>'required',
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


}
