<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function create(Request $request){
        $request->validate([
            'title'=>['required', 'string', max(255)],
            'description'=>['required', 'string'],
        ]);

        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);
    }

    public function store(){

    }
}
