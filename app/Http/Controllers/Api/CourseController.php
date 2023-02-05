<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CourseService;

class CourseController extends Controller
{
    public function createCourse(Request $request){
        return CourseService::createCourse($request);
    }

    public function allCourses(Request $request){
        return CourseService::allCourses($request);
    }

    public function updateCourse(Request $request){
        return CourseService::updateCourse($request);
    }

    public function destroyCourse(Request $request){
        return CourseService::destroyCourse($request);
    }
}
