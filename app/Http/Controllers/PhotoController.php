<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PhotoController extends Controller
{

    public function storeImage(Request $request,$id){
        // Employee::

        if(!$request->hasFile('image')) {
            return response()->json(['upload_file_not_found'], 400);
        }

        $file = $request->file('image');
        if(!$file->isValid()) {
            return response()->json(['invalid_file_upload'], 422);
        }

        $path = public_path() . '/images';
        $file->move($path, $file->getClientOriginalName());
        Employee::where('id',$id)->update(array('path_image'=> $file->getClientOriginalName()));

        return response()->json("Image uploaded");

    }

    public function getImage($id){
        $imageName =Employee::where('id',$id)->first();
        $path = public_path().'/images/'.$imageName->path_image;
        return response()->file(public_path('/images/'.$imageName->path_image));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
