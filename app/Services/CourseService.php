<?php

namespace App\Services;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Customer;
use App\Models\Course;
use App\Models\Certificate_layout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CourseService{

    public static function createCourse(Request $request){
        try{

            $tenant = Tenant::where('id', auth()->user()->id)->first();
            $certificate_layout = Certificate_layout::where('tenant_id', $tenant->id)->first();
            $course = Course::create([
                "customer_id" => $request->customer_id,
                "certificate_layout_id" => $certificate_layout->id,
                "code" => $request->code,
                "name" => $request->name,
                "description" => $request->description,
                "date_from" => date('Y-m-d', strtotime($request->date_from)),
                "date_untill" => date('Y-m-d', strtotime($request->date_untill)),
                "info" => null
            ]);

            $response = response()->json(
                ['success' => true, 'message' => "Course Created Succesfully!"],
                200
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = response()->json(
                ['success' => false, 'message' => $e],
                400
            );
        }

        return $response;

    }

    public static function allCourses(Request $request){
        try{
            $totalCount = 0;
            $tenant = Tenant::where('id',auth()->user()->id)->first();
            $courses = Course::where('certificate_layout_id', $tenant->id);

            if($request->has('requireTotalCount')){
                $totalCount = $courses->count();
            }

            if($request->has('skip')){
                $courses->skip($request->skip)->take($request->take);
            }

            if($request->has('sort')){
                $sort = json_decode($request->sort,1);
                if(count($sort)){
                    $courses->orderBy($sort[0]['selector'], $sort[0]['desc'] ? 'DESC' : 'ASC');
                }

            }

            $courses = $courses->get();

            if ( $courses) {
                $response = response()->json(
                    ['success' => true, 'data' => $courses, 'totalCount' => $totalCount],
                    200
                );
            } else {
                $response = response()->json(
                    ['success' => false, "data" => [],'totalCount' => $totalCount, 'message' => 'No Data Found!!'],
                    401
                );
            }
        }catch(Exception $e){
            Log::error($e->getMessage());

            $response = response()->json(
                ['success' => false, 'message' => $e],
                400
            );
        }

        return $response;

    }

    /**
     * Update Company User in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function updateCourse(Request $request)
    {
        try {
            $course = Course::where('id', $request->id)->first();
            $course->code = $request->code;
            $course->name = $request->name;
            $course->description = $request->description;
            $course->date_from = date("Y-m-d", strtotime($request->date_from));
            $course->date_untill = date("Y-m-d", strtotime($request->date_untill));
            $course->save();

            $response = response()->json(
                ['success' => true, 'message' => 'Course has been updated successfully'],
                200
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = response()->json(
                ['success' => false, 'message' => $e],
                400
            );
        }

        //return $response;

    }

    /**
     * Delete teanat customer from storage.
     */
    public static function destroyCourse(Request $request)
    {
        try {

            Course::where('id', $request->id)->delete();

            $response = response()->json(
                [
                    'success' => true,
                    'message' => 'Course     has been deleted successfully!',
                ],
                200
            );

        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = response()->json(
                ['success' => false, 'message' => $e],
                400
            );
        }

        return $response;
    }    

}


?>
