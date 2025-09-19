<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Course;

class DashboardController extends Controller
{
    public function admin()
    {
        Gate::authorize('admin-access');
        
        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_instructors' => User::where('role', 'instructor')->count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_courses' => Course::count(),
            'recent_users' => User::latest()->take(5)->get(['id', 'name', 'email', 'role', 'created_at']),
        ];

        return inertia('Dashboard/Admin', [
            'user' => Auth::user(),
            'stats' => $stats,
            'usersLink' => route('admin.users.index'),
        ]);
    }

    public function instructor()
    {
        Gate::authorize('instructor-access');
        
        $user = Auth::user();
        $courses = Course::where('user_id', $user->id)->get();
        
        $stats = [
            'my_courses' => $courses->count(),
            'total_students' => 0, 
            // 'recent_courses' => $courses->latest()->take(3)->get(),
        ];

        return inertia('Dashboard/Instructor', [
            'user' => $user,
            'stats' => $stats,
            'courses' => $courses,
        ]);
    }

    public function student()
    {
        $user = Auth::user();
        
        $stats = [
            'enrolled_courses' => 0, 
            'completed_courses' => 0,
            'available_courses' => Course::count(),
        ];

        return inertia('Dashboard/Student', [
            'user' => $user,
            'stats' => $stats,
        ]);
    }
}