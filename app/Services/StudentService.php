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
     * Store a newly student resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    
    public static function store(Request $request)
    {
        try {

            $tenant = User::where('id', auth()->user()->id)->first();
            $course = Course::where('tenant_id', $tenant->id)->first();

            if(!empty($request->course_id)){
                $course_id = $request->course_id;
            }else{
                $course_id = null;
            }

            if($course){
                $student = Student::create([
                    'tenant_id' => $tenant->id,
                    'course_id' => $course_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'birth_date' => date('Y-m-d H:i:s', strtotime($request->birth_date)),
                    'birth_place' => $request->birth_place,
                    'info' => null,
                ]);
            }

            $response = response()->json(
                [
                    'success' => true,
                    'message' => 'Student created succesfully!'
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
                    return $response = response()->json(
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

            $response = response()->json(
                [
                    'success' => true,
                    'message' => 'Student Updated Succesfully!'
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
            return $response = response()->json(
                [
                    'success' => true,
                    'message' => 'Student has been deleted successfully!',
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
                            $response = response()->json(['success' => false, 'status' => 401, 'message' => "Please upload a valid csv file"]);
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

                $response = response()->json(
                    [
                        'success' => true,
                        'courses' => $courses
                    ],
                    200
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

}

?>
