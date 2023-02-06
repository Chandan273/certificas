<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StudentService;

class StudentController extends Controller
{
    public function createStudent(Request $request){
        return StudentService::createStudent($request);
    }

    public function allStudents(Request $request){
        return StudentService::allStudents($request);
    }

    public function updateStudent(Request $request){
        return StudentService::updateStudent($request);
    }

    public function destroyStudent(Request $request){
        return StudentService::destroyStudent($request);
    }

    public function importStudentCsv(Request $request){
        return StudentService::importStudentCsv($request);
    }
}
