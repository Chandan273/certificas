<?php

namespace App\Services;

use App\Models\User;
use App\Models\Customer;
use App\Models\Tenant;
use App\Models\Student;
use App\Models\Course;
use App\Models\Certificate_layout;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class CertificateService
{

    /**
     * Store a newly certificate resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    
    public static function store(Request $request)
    {
        try {
            $tenant = User::where('id', auth()->user()->id)->first();
            $certificate_layout = Certificate_layout::where('tenant_id',$tenant->id)->first();
            $student = Student::where('id',$request->student_id)->first();

            $course = Certificate::create([
                'student_id' => $request->student_id,
                'course_id' => $student->course_id,
                'certificate_layout_id' => $certificate_layout->id,
                'description' => $request->description,
                'valid_from' => date('Y-m-d H:i:s', strtotime($request->valid_from)),
                'valid_untill' => date('Y-m-d H:i:s', strtotime($request->valid_untill)),
                'info' => json_encode($request->info),
            ]);

            $response = response()->json(
                [
                    'success' => true,
                    'message' => 'Certificate created succesfully!'
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
     * Display a listing of the certificate.
     *
     * @return \Illuminate\Http\Response
     */
    public static function index(Request $request)
    {
        try {
            $totalCount = 0;
            if (
                auth()
                    ->user()
                    ->hasRole('superadmin')
            ) {
                $certificates = Certificate::all();
            } else {
                $tenant = User::where('id', auth()->user()->id)->first();

                if ($tenant) {
                    $certificate_layout = Certificate_layout::where('tenant_id',$tenant->id)->first();
                    $certificates = Certificate::where('certificate_layout_id',$certificate_layout->id);
                } else {
                    $response = response()->json(
                        ['success' => false, 'message' => 'No data found'],
                        400
                    );
                }
            }

            if ($request->has('requireTotalCount')) {
                $totalCount = $certificates->count();
            }

            if ($request->has('skip')) {
                $certificates->skip($request->skip)->take($request->take);
            }

            if ($request->has('sort')) {
                $sort = json_decode($request->sort, 1);
                if (count($sort)) {
                    $certificates->orderBy(
                        $sort[0]['selector'],
                        $sort[0]['desc'] ? 'DESC' : 'ASC'
                    );
                }
            }

            $certificates = $certificates->get();

            if ($certificates) {
                $response = response()->json(
                    [
                        'success' => true,
                        'data' => $certificates,
                        'totalCount' => $totalCount,
                    ],
                    200
                );
            } else {
                $response = response()->json(
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

            $response = response()->json(
                ['success' => false, 'message' => $e],
                400
            );
        }

        return $response;
    }

    /**
     * Update the specified certificate resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function update(Request $request)
    {
        try {

            $certificate = Certificate::where('id', $request->id)->first();
            $certificate->student_id = $request->student_id;
            $certificate->course_id = $request->course_id;
            $certificate->description = $request->description;
            $certificate->valid_from = date('Y-m-d H:i:s', strtotime($request->valid_from));
            $certificate->valid_untill = date('Y-m-d H:i:s', strtotime($request->valid_untill));
            $certificate->info = json_encode($request->info);
            $certificate->save();

            $response = response()->json(
                [
                    'success' => true,
                    'message' => 'Certificate Updated Succesfully!'
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
     * Delete record with the certificate id in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */  
    public static function destroy(Request $request)
    {
        try {
            $course = Certificate::where('id', $request->id)->first();
            $course->delete();
            return $response = response()->json(
                [
                    'success' => true,
                    'message' => 'Certificate has been deleted successfully!',
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
