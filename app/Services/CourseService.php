<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Course;
use App\Models\Certificate_layout;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class CourseService
{
    /**
     * Store a newly course resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    
    public static function store(Request $request)
    {
        try {

            $tenant = User::where('id', auth()->user()->id)->first();
            $customer = Customer::where('tenant_id', $tenant->id)->first();
            $certificate_layout = Certificate_layout::where('tenant_id',$tenant->id)->first();
            $course = Course::create([
                'tenant_id' => $tenant->id,
                'certificate_layout_id' => $certificate_layout->id,
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
                'date_from' => date('Y-m-d H:i:s', strtotime($request->date_from)),
                'date_untill' => date('Y-m-d H:i:s', strtotime($request->date_untill)),
                'info' => null,
            ]);

            $response = response()->json(
                [
                    'success' => true,
                    'message' => 'Course created succesfully!'
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
                    return $response = response()->json(
                        ['success' => false, 'message' => 'No data found'],
                        400
                    );
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
                return $response = response()->json(
                    [
                        'success' => true,
                        'data' => $courses,
                        'totalCount' => $totalCount,
                    ],
                    200
                );
            } else {
                return $response = response()->json(
                    [
                        'success' => false,
                        'data' => [],
                        'totalCount' => $totalCount,
                        'message' => 'No Data Found!!',
                    ],
                    401
                );
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return $response = response()->json(
                ['success' => false, 'message' => $e],
                400
            );
        }
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

            $course = Course::where('id', $request->id)->first();
            $course->code = $request->code;
            $course->name = $request->name;
            $course->description = $request->description;
            $course->date_from = date('Y-m-d H:i:s', strtotime($request->date_from));
            $course->date_untill = date('Y-m-d H:i:s', strtotime($request->date_untill));
            $course->info = null;
            $course->save();

            $response = response()->json(
                [
                    'success' => true,
                    'message' => 'Course Updated Succesfully!'
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
            return $response = response()->json(
                [
                    'success' => true,
                    'message' => 'Course has been deleted successfully!',
                ],
                200
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return $response = response()->json(
                ['success' => false, 'message' => $e],
                400
            );
        }
    }
}

?>
