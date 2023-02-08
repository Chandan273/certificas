<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CourseService;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Store a newly course resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'code' => 'required',
            'name' => 'required',
            'description' => 'required',
            'date_from' => 'required',
            'date_untill' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'false', 'error'=>$validator->errors()], 422);
        }

        return CourseService::store($request);
    }

    /**
     * Display a listing of the courses.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        return CourseService::index($request);
    }

    /**
     * Update the specified courses resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){

        $validator = Validator::make($request->all(),[
            'code' => 'required',
            'name' => 'required',
            'description' => 'required',
            'date_from' => 'required',
            'date_untill' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'false', 'error'=>$validator->errors()], 422);
        }

        return CourseService::update($request);
    }

    /**
     * Delete record with the course id in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */    
    public function destroy(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        return CourseService::destroy($request);
    }
}
