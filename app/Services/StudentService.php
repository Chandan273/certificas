<?php

namespace App\Services;

use App\Models\User;
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
        try {
            $totalCount = 0;

            if(auth()->user()->hasRole('superadmin')) {
                $students = Student::all();
            }
            else {
                $tenant = User::where('id', auth()->user()->id)->first();
                if($tenant) {
                    $students = Student::join('courses', 'students.course_id', '=', 'courses.id')
                    ->join('tenants', 'courses.tenant_id', '=', 'tenants.id')
                    ->join('customers', 'tenants.id', '=', 'customers.tenant_id')
                    ->select('students.*', 'courses.name as course_name', 'customers.name as customer_name', 'tenants.name as tenant_name');
                }
                else {
                    $response = response()->json(
                        ['success' => false, 'message' => 'No data found'],
                        400
                    );
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
                $response = response()->json(
                    [
                        'success' => true,
                        'data' => $students,
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
    
    public static function importStudentCsv(Request $request)
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

                // File upload location
                $location = 'uploads';

                // Upload file
                $file->move($location, $filename);

                // Import CSV to Database
                $filepath = public_path($location . "/" . $filename);

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
                            $response = response()->json(['success' => false, 'status' => 401, 'message' => "Please upload a valid csv file"]);
                        }

                    }
                    $importData_arr[] = [
                        'course_id' => null,
                        'name' => $filedata[0],
                        'email' => $filedata[1],
                        'birth_date' => date('Y-m-d', strtotime($filedata[2])),
                        'birth_place' => $filedata[3],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    $i++;
                }
                fclose($file);

                try {
                    Student::insert($importData_arr);
                    $response = response()->json(
                        [
                            'success' => true,
                            'message' => 'Student Data Imported successfully!'
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
            }
        }

        return $response;

    }

}


?>
