<?php

namespace App\Services;

use App\Models\User;
use App\Models\Customer;
use App\Models\Tenant;
use App\Models\Course;
use App\Models\Student;
use App\Models\Certificate_layout;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;

class StudentService
{
    /**
     * Display the specified student resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function index(Request $request)
    {
        try {
            $totalCount = 0;
            if(auth()->user()->hasRole('superadmin')) {
                $students = Student::all();
            }
            else {
                $tenant = User::where('id', auth()->user()->id)->first();
                if($tenant) {
                    $students = Student::where(
                        'tenant_id',
                        $tenant->id
                    );
                }
                else {
                    $response = ['success' => false, 'message' => 'No data found', 'statusCode' => 404];
                }
            }


            if ($request->has('requireTotalCount')) {
                $totalCount = $students->count();
            }

            if ($request->has('skip')) {
                $students->skip($request->skip)->take($request->take);
            }

            if ($request->has('sort')) {
                $sort = json_decode($request->sort, 1);
                if (count($sort)) {
                    $students->orderBy(
                        $sort[0]['selector'],
                        $sort[0]['desc'] ? 'DESC' : 'ASC'
                    );
                }
            }

            $students = $students->get();

            if ($students) {

                $response = ['success' => true, 'data' => $students, 'totalCount' => $totalCount, 'statusCode' => 200];
            } else {

                $response = ['success' => false,'data' => [], 'totalCount' => $totalCount, 'message' => 'No Data Found!!', 'statusCode' => 404];
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500];
        }

        return $response;

    }

    /**
     * Store a newly student resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    
    public static function store(Request $request)
    {
        try {
            $course = Course::where('tenant_id', auth()->user()->id)->first();

            if(!empty($request->course_id)){
                $course_id = $request->course_id;
            }else{
                $course_id = null;
            }

            if($course){
                $student = Student::create([
                    'tenant_id' => auth()->user()->id,
                    'course_id' => $course_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'birth_date' => date('Y-m-d H:i:s', strtotime($request->birth_date)),
                    'birth_place' => $request->birth_place,
                    'info' => null,
                ]);
            }

            $response = ['success' => true, 'message' => 'Student created succesfully!', 'statusCode' => 200 ];
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500 ];
        }

        return $response;
    }

    /**
     * Update the specified student resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */    
    public static function update(Request $request)
    {
        try {

            $student = Student::where('id', $request->id)->first();
            $student->course_id = $request->course_id;
            $student->name = $request->name;
            $student->email = $request->email;
            $student->birth_date = date('Y-m-d H:i:s', strtotime($request->birth_date));
            $student->birth_place = $request->birth_place;
            $student->info = json_encode($request->info);
            $student->save();

            $response = ['success' => true, 'message' => 'Student Updated Succesfully!', 'statusCode' => 200 ];
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500 ];
        }

        return $response;
    }

    /**
     * Delete record with the student id in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */    
    public static function destroy(Request $request)
    {
        try {
            $student = Student::where('id', $request->id)->first();
            $student->delete();

            $response = ['success' => true, 'message' => 'Student has been deleted successfully!', 'statusCode' => 200];
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500 ];
        }

        return $response;
    }

    /**
     * Import a newly student resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */   
    public static function importCsv(Request $request)
    {

        $file = $request->file('file');

        // File Details
        $filename = time().".".$file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();

        // Valid File Extensions
        $valid_extension = array("csv");

        // 2MB in Bytes
        $maxFileSize = 2097152;

        // Check file extension
        if (in_array(strtolower($extension), $valid_extension)) {

            // Check file size
            if ($fileSize <= $maxFileSize) {

                // Store the file in the public disk
                Storage::disk('public')->put($filename, file_get_contents($file));

                // Reading file
                $filepath = storage_path('app/public/' . $filename);

                // Reading file
                $file = fopen($filepath, "r");

                $importData_arr = array();
                $i = 0;

                while (($filedata = fgetcsv($file, 1000, ",")) !== false) {
                    $num = count($filedata);

                    if ($i == 0) {
                        if ($filedata[0] == "Courses" || $filedata[1] == "Name" || $filedata[2] == "Email" || $filedata[3] == "Birth date" || $filedata[4] == "Birth place") {
                            $i++;
                            continue;
                        } else {
                            $response = ['success' => false, 'message' => "Please upload a valid csv file", 'statusCode' => 401];
                        }

                    }
                    $importData_arr[] = [
                        'tenant_id' => auth()->user()->id,
                        'course_id' => null, //$filedata[0],
                        'name' => $filedata[1],
                        'email' => $filedata[2],
                        'birth_date' => date('Y-m-d', strtotime($filedata[3])),
                        'birth_place' => $filedata[4],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    $i++;
                }
                fclose($file);

                unlink($filepath);

                try {
                    Student::upsert($importData_arr, ['name', 'email']);

                    $response = ['success' => true, 'message' => 'Student Data Imported successfully!', 'statusCode' => 200];
                } catch (Exception $e) {
                    Log::error($e->getMessage());

                    $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500];
                }
            }
        }

        return $response;

    }

    /**
     * Display the specified student courses resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */    
    public static function courses(Request $request)
    {
        try {

            if(auth()->user()->hasRole('superadmin')) {
                $course = Course::all();
            }
            else {
                $tenant = User::where('id', auth()->user()->id)->first();
                if($tenant) {
                    $courses = Course::where(
                        'tenant_id',
                        $tenant->id
                    )->get();
                }

                $response = ['success' => true, 'courses' => $courses, 'message' => 'all courses listing', 'statusCode' => 200];
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500];
        }

        return $response;
    }    

}

?>
