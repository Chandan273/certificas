<?php

namespace App\Services;

use Dompdf\Dompdf;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Course;
use App\Models\Student;
use App\Models\Customer;
use App\Models\Certificate;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Tenant_course;
use Spatie\Permission\Models\Role;
use App\Models\Certificate_layout;

class CourseService
{
    /**
     * Display a listing of the courses.
     *
     * @return \Illuminate\Http\Response
     */
    public static function index(Request $request)
    {
        try {
            $totalCount = 0;
            if(auth()->user()->hasRole('superadmin')) {
                $courses = Course::all();
            }
            else {
                $tenant = User::where('id', auth()->user()->id)->first();
                if($tenant) {
                    $courses = Course::where(
                        'tenant_id',
                        $tenant->id
                    );
                }
                else {

                    $response = ['success' => false, 'message' => 'No data found', 'statusCode' => 401];
                }
            }


            if ($request->has('requireTotalCount')) {
                $totalCount = $courses->count();
            }

            if ($request->has('skip')) {
                $courses->skip($request->skip)->take($request->take);
            }

            if ($request->has('sort')) {
                $sort = json_decode($request->sort, 1);
                if (count($sort)) {
                    $courses->orderBy(
                        $sort[0]['selector'],
                        $sort[0]['desc'] ? 'DESC' : 'ASC'
                    );
                }
            }

            $courses = $courses->get();

            if ($courses) {

                $response = ['success' => true, 'data' => $courses, 'totalCount' => $totalCount, 'statusCode' => 200];
            } else {

                $response = ['success' => false, 'data' => [], 'totalCount' => $totalCount, 'message' => 'No Data Found!!', 'statusCode' => 404];
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500];
        }

        return $response;
    }

    /**
     * Store a newly course resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    
    public static function store(Request $request)
    {
        try {

            $date_from = empty($request->date_from) ? Null : date('Y-m-d H:i:s', strtotime($request->date_from));
            $date_untill = empty($request->date_untill) ? Null : date('Y-m-d H:i:s', strtotime($request->date_untill));
            
            $tenant = User::where('id', auth()->user()->id)->first();
            $customer = Customer::where('tenant_id', $tenant->id)->first();
            $certificate_layout = Certificate_layout::where('tenant_id',$tenant->id)->first();
            $course = Course::create(array_merge($request->all(),[
                'tenant_id' => $tenant->id,
                'certificate_layout_id' => $certificate_layout->id,
                'date_from' => $date_from,
                'date_untill' => $date_untill,
            ]));

            $response = ['success' => true, 'course'=> $course, 'message' => 'Course created succesfully!', 'statusCode' => 200 ];
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500];
        }

        return $response;
    }

    /**
     * Update the specified courses resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function update(Request $request)
    {
        try {

            $date_from = empty($request->date_from) ? Null : date('Y-m-d H:i:s', strtotime($request->date_from));
            $date_untill = empty($request->date_untill) ? Null : date('Y-m-d H:i:s', strtotime($request->date_untill));

            $course = Course::where('id', $request->id)->first();
            $course->update(array_merge($request->all(),[
                'date_from' => $date_from,
                'date_untill' => $date_untill,
            ]));

            $response = ['success' => true, 'message' => 'Course Updated Succesfully!', 'statusCode' => 200 ];
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500];
        }

        return $response;
    }

    /**
     * Delete record with the course id in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function destroy(Request $request)
    {
        try {
            $course = Course::where('id', $request->id)->first();
            $course->delete();

            // Student::where('course_id', $request->id)->delete();
            // Certificate::where('course_id', $request->id)->delete();

            $response = ['success' => true, 'message' => 'Course has been deleted successfully!', 'statusCode' => 200 ];
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500];
        }

        return $response;
    }

    /**
     * Store a newly tenant courses resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function createTenantCourses(Request $request){
        try {
            $tenant_course = Tenant_course::withTrashed()
                ->where('course_id', $request->course_id)
                ->where('tenant_id', auth()->user()->id)
                ->first();

            if ($tenant_course) {
                $tenant_course->fill($request->all())->save();

                // $studentIds = $tenant_course->students;
                // $data = [];
                // foreach ($studentIds as $studentId) {
                //     $data[] = [
                //         'customer_id' => $studentId,
                //         'tenant_id' => $tenant_id,
                //         'course_id' => $tenant_course->course_id,
                //         'created_at' => now(),
                //         'updated_at' => now(),
                //         'deleted_at' => null,
                //     ];
                // }

                // Student::upsert($data, ['customer_id', 'tenant_id', 'course_id'], ['name', 'email', 'birth_date', 'birth_place', 'info', 'created_at', 'updated_at', 'deleted_at']);

                $response = ['success' => true, 'message' => "Tenant Course updated successfully!", 'statusCode' => 200];
            } else {
                $tenant_course = Tenant_course::create(array_merge($request->all(), [
                    'tenant_id' => auth()->user()->id,
                    'students' => json_encode($request->students),
                ]));

                $response = ['success' => true, 'message' => "Tenant Course created successfully!", 'statusCode' => 200];
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500];
        }

        //return $response;
    } 

    /**
     * List of Tenant courses.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function tenantCourse(Request $request){
        try {
            $tenantCourse = Tenant_course::where('course_id',  $request->course_id)->first();

            $response = ['success' => true, "tenantCourse" => $tenantCourse, 'statusCode' => 200];
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500];
        }

        return $response;
    }
}

?>
