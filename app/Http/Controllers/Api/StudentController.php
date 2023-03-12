<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StudentService;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Store a newly student resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            // 'customer_id' => 'required',
            // 'course_id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'birth_date' => 'required',
            'birth_place' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        return StudentService::store($request);
    }

    /**
     * Display the specified student resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        return StudentService::index($request);
    }

    /**
     * Update the specified student resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        return response()->json(StudentService::update($request));
    }

    /**
     * Delete record with the student id in storage.
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

        return StudentService::destroy($request);
    }

    /**
     * Import a newly student resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    
    public function importStudentCsv(Request $request){
        return StudentService::importCSV($request);
    }

    /**
     * Display the specified student courses resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function studentCourses(Request $request){
        return StudentService::courses($request);
    }

}
