<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function admin()
    {
        Gate::authorize('admin-access'); 
        return inertia('Dashboard', [
            'role' => Auth::user()->role,
            'message' => 'Welcome to the Admin Dashboard',
        ]);
    }

    public function instructor()
    {
        Gate::authorize('instructor-access'); 
        return inertia('Dashboard', [
            'role' => Auth::user()->role,
            'message' => 'Welcome to the Instructor Dashboard',
        ]);
    }

    public function student()
    {
        // dd(Auth::user()->role);
        return inertia('Dashboard', [
            'role' => Auth::user()->role,
            'message' => 'Welcome to the Student Dashboard',
        ]);
    }
}
