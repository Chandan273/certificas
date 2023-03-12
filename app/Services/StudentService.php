<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Course;
use App\Models\Student;
use App\Models\Customer;
use Illuminate\Support\Str;
use App\Models\Certificate;
use Illuminate\Http\Request;
use App\Models\Tenant_course;
use Spatie\Permission\Models\Role;
use App\Models\Certificate_layout;
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
            $customer_id = $request->get('customer_id');
            $course_id = $request->get('course_id');

            if(auth()->user()->hasRole('superadmin')) {  // You can't do everything with same function, 
                $students = Student::all();
            }
            else {
                $tenant = User::where('id', auth()->user()->id)->first();
                if($tenant) {
                    $students = Student::where('tenant_id', $tenant->id)
                        ->whereNull('deleted_at');

                    // Add filter based on customer_id
                    if ($customer_id) {
                        $students->where('students.customer_id', $customer_id);
                    }
                    if ($course_id) {  // I need data for both cases, all students as well as by course in singel calle
                        $students->where('students.course_id', $course_id);
                    }
                }
                else {
                    $response = ['success' => false, 'message' => 'No data found', 'statusCode' => 401];
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
                $response = ['success' => false, 'data' => [], 'totalCount' => $totalCount, 'message' => 'No Data Found!!', 'statusCode' => 401];
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500];
        }

        return $response;
    }

    /**
     * Store student resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    
    public static function store(Request $request)
    {
        try {
            $student = Student::withTrashed()
                ->where('course_id', $request->course_id)
                ->where('email', $request->email)
                ->where('tenant_id', auth()->user()->id)
                ->first();

            if ($student) {
                $student->fill($request->all())->save();

                $response = ['success' => true, 'message' => "Student updated successfully!", 'statusCode' => 200];
            } else {
                $student = Student::create(array_merge($request->all(), [
                    'tenant_id' => auth()->user()->id,
                ]));

                $response = ['success' => true, 'message' => "Student created successfully!", 'statusCode' => 200];
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500];
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
            $student->update(array_merge($request->all(), [
                'birth_date' => date('Y-m-d H:i:s', strtotime($request->birth_date)),
            ]));

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

            $certificate = Certificate::where('student_id', $request->id)->delete();

            $response = ['success' => true, 'message' => 'Student has been deleted successfully!', 'statusCode' => 200];
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $response = ['success' => false, 'message' => $e->getMessage(), 'statusCode' => 500 ];
        }

        return $response;
    }

    /**
     * Import Student CSV resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */   
    public static function importCSV(Request $request)
    {
        $file = $request->file('file');

        // File Details
        $filename = time() . "." . $file->getClientOriginalName();
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

                        if ($filedata[0] == "Name" || $filedata[1] == "Email" || $filedata[2] == "Birth date" || $filedata[3] == "Birth place") {
                            $i++;
                            continue;
                        } else {
                            $response = ['success' => false, 'message' => "Please upload a valid csv file", 'statusCode' => 401];
                        }
                    }

                    // Check if any field is empty
                    if (empty($filedata[0]) || empty($filedata[1]) || empty($filedata[2]) || empty($filedata[3])) {
                        fclose($file);
                        unlink($filepath);
                        return ['success' => false, 'message' => "Please ensure all fields are filled in the CSV file", 'statusCode' => 401];
                    }

                    $student = Student::withTrashed()->where('course_id', $request->course_id)->where('email', $filedata[1])->first();

                    if ($student) {
                        // If the student is soft deleted, restore it
                        if ($student->trashed()) {
                            $student->restore();
                        }

                        // Update the student data
                        $student->customer_id = $request->customer_id;
                        $student->name = $filedata[0];
                        $student->birth_date = date('Y-m-d', strtotime($filedata[2]));
                        $student->birth_place = $filedata[3];
                        $student->save();
                        
                    } else {

                        $importData_arr[] = [
                            'tenant_id' => auth()->user()->id,
                            'customer_id' => $request->customer_id,
                            'course_id' => $request->course_id,
                            'name' => $filedata[0],
                            'email' => $filedata[1],
                            'birth_date' => date('Y-m-d', strtotime($filedata[2])),
                            'birth_place' => $filedata[3],
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
                    }

                    $i++;
                }

                fclose($file);
                unlink($filepath);

                try {
                    if (!empty($importData_arr)) {
                        Student::upsert($importData_arr, ['email']);
                    }

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
                    )->whereNull('deleted_at')->get();
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
