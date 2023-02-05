<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Course;
use App\Models\Customer;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class StudentService{

    public static function allStudents(Request $request){
        try{
            $totalCount = 0;
            $students = Student::join('courses', 'students.course_id', '=', 'courses.id')
                  ->join('customers', 'courses.customer_id', '=', 'customers.id')
                  ->join('tenants', 'customers.tenant_id', '=', 'tenants.id')
                  ->select('students.*', 'courses.name as course_name', 'customers.name as customer_name', 'tenants.name as tenant_name');

            if($request->has('requireTotalCount')){
                $totalCount = $students->count();
            }

            if($request->has('skip')){
                $students->skip($request->skip)->take($request->take);
            }

            if($request->has('sort')){
                $sort = json_decode($request->sort,1);
                if(count($sort)){
                    $students->orderBy($sort[0]['selector'], $sort[0]['desc'] ? 'DESC' : 'ASC');
                }
            }

            $students = $students->get();

            if ( $students) {
                $response = response()->json(
                    ['success' => true, 'data' => $students, 'totalCount' => $totalCount],
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

    public static function createStudent(Request $request){
        try{

            $student = Student::create([
                "course_id" => $request->course_id,
                "name" => $request->name,
                "email" => $request->email,
                "birth_date" => date('Y-m-d', strtotime($request->birth_date)),
                "birth_place" => $request->birth_place,
                "info" => null
            ]);

            $response = response()->json(
                ['success' => true, 'message' => "Customer Created Succesfully!"],
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


    /**
     * Update Company User in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function updateStudent(Request $request)
    {
        try {
            $student = Student::where('id', $request->id)->first();
            $student->course_id = $request->course_id;
            $student->name = $request->name;
            $student->email = $request->email;
            $student->birth_date = date('Y-m-d', strtotime($request->birth_date));
            $student->birth_place = $request->birth_place;
            $student->save();

            $response = response()->json(
                ['success' => true, 'message' => 'Student has been updated successfully'],
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
    public static function destroyStudent(Request $request)
    {
        try {

            Student::where('id', $request->id)->delete();

            $response = response()->json(
                [
                    'success' => true,
                    'message' => 'Student has been deleted successfully!',
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
