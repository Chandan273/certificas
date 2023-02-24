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
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;

class CertificateService
{
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
                //$certificates = Certificate::with('Student')->where('certificate_layout_id',auth()->user()->id);
                $certificates = Certificate::with('Student')
                ->where('certificate_layout_id', auth()->user()->id)
                ->whereHas('course', function ($query) {
                    $query->whereNull('deleted_at');
                });
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

            $certificates = $certificates->whereNull('deleted_at')->get();

            if ($certificates) {

                $response = ['success' => true, 'data' => $certificates, 'totalCount' => $totalCount, 'statusCode' => 200];
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
     * Store a newly certificate resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    
    public static function store(Request $request)
    {
        try {
            $certificate_layout = Certificate_layout::where('tenant_id',auth()->user()->id)->first();
            $student = Student::where('id',$request->student_id)->first();

            $course = Certificate::create(array_merge($request->all(),[
                'course_id' => $student->course_id,
                'certificate_layout_id' => $certificate_layout->id,
                'valid_from' => date('Y-m-d H:i:s', strtotime($request->valid_from)),
                'valid_untill' => date('Y-m-d H:i:s', strtotime($request->valid_untill)),
            ]));

            $response = ['success' => true, 'statusCode' => 200, 'message' => 'Certificate created succesfully!' ];
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500];
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

            $certificate->update(array_merge($request->all(), [
                'valid_from' => date('Y-m-d H:i:s', strtotime($request->valid_from)),
                'valid_untill' => date('Y-m-d H:i:s', strtotime($request->valid_untill))
            ]));

            $response = ['success' => true, 'message' => 'Certificate Updated Succesfully!', 'statusCode' => 200];
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500];
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

            $response = ['success' => true, 'message' => 'Certificate has been deleted successfully!', 'statusCode' => 200 ];
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500];
        }

        return $response;
    }

    /**
     * Generate PDF for the certificate.
     *
     * @return \Illuminate\Http\Response
     */
    public static function generatePdf(Request $request)
    {
        try{
            if (!$request->has('student_id')) {
                $response = ['success' => false, 'message' => 'Student ID is missing', 'statusCode' => 400];
            }
            
            $student = Student::where("id", $request->student_id)->first();

            if (!file_exists(storage_path('app/public/qr'))) {
                mkdir(storage_path('app/public/qr'), 0777, true);
            }
            
            $qrCodePath = storage_path('app/public/qr/qrcode.png');
            QrCode::format('png')
                ->size(200)
                ->generate('www.google.com', $qrCodePath);
    
            $studentData = [
                'name' => $student->name,
                'email' => $student->email,
                'birth_date' => $student->birth_date,
                'birth_place' => $student->birth_place,
                'qrCodePath' => $qrCodePath
            ];
            
            $pdf = PDF::loadView('pdf.certificate', $studentData);
    
            $response = $pdf->download('certificas.pdf');
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500];
        }

        return $response;
    }
}

?>
