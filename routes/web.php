<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return inertia('Welcome');
});

// Auth routes
Route::middleware('auth')->group(function () {
    
    // All auth users
    Route::get('/dashboard', function () {
        $user = Auth::user();
        
        // Redirect based on role
        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'instructor' => redirect()->route('instructor.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default => redirect()->route('student.dashboard'),
        };
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::resource('categories',CategoryController::class);
    });

    // Instructor routes  
    Route::middleware(['role:instructor,admin'])->prefix('instructor')->name('instructor.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'instructor'])->name('dashboard');
        Route::resource('courses', InstructorCourseController::class);
    });

    // Student routes (all roles can access)
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'student'])->name('dashboard');
    });

    // Public
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('courses.show');
});

require __DIR__.'/auth.php';
