<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Category;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::published()->with(['category', 'instructor']);

        // Filters
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('price')) {
            if ($request->price === 'free') {
                $query->where('price', 0);
            } elseif ($request->price === 'paid') {
                $query->where('price', '>', 0);
            }
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('short_description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Sort courses
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('id', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            default:
                $query->latest();
        }

        $courses = $query->paginate(12)->withQueryString();

        $categories = Category::active()->withCount('courses')->orderBy('name')->get();

        return inertia('Courses/Index', [
            'courses' => $courses,
            'categories' => $categories,
            'filters' => $request->only(['category', 'level', 'price', 'search', 'sort']),
        ]);
    }

    public function show(Course $course)
    {
        // Only show published courses to non-owners
        if ($course->status !== 'published' && $course->user_id !== auth()->id()) {
            abort(404);
        }

        $course->load(['category', 'instructor']);

        // Get related courses
        $relatedCourses = Course::published()
                               ->where('category_id', $course->category_id)
                               ->where('id', '!=', $course->id)
                               ->with(['category', 'instructor'])
                               ->limit(3)
                               ->get();

        return inertia('Courses/Show', [
            'course' => $course,
            'relatedCourses' => $relatedCourses,
        ]);
    }
}