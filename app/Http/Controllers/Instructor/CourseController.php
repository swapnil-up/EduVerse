<?php
namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Services\FileUploadService;

class CourseController extends Controller
{
    protected FileUploadService $fileService;

    public function __construct(FileUploadService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index(Request $request)
    {
        $query = Course::where('user_id', Auth::id())->with('category');

        // Filters 
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $courses = $query->latest()->paginate(12)->withQueryString();

        $categories = Category::active()->orderBy('name')->get();

        return inertia('Instructor/Courses/Index', [
            'courses' => $courses,
            'categories' => $categories,
            'filters' => $request->only(['category', 'status', 'search']),
        ]);
    }

    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();

        return inertia('Instructor/Courses/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0|max:9999.99',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_hours' => 'nullable|integer|min:1|max:1000',
            'requirements' => 'nullable|array',
            'requirements.*' => 'string|max:255',
            'what_you_learn' => 'nullable|array',
            'what_you_learn.*' => 'string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Handle thumbnail upload using service
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $this->fileService->uploadCourseThumbnail($request->file('thumbnail'));
        }

        // Add instructor and slug
        $validated['user_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['title']);

        // Ensure slug is unique
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Course::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        Course::create($validated);

        return redirect()->route('instructor.courses.index')
                        ->with('success', 'Course created successfully!');
    }

    public function show(Course $course)
    {
        // Ensure instructor can only view their own courses
        if ($course->user_id !== Auth::id()) {
            abort(403);
        }

        $course->load('category');

        return inertia('Instructor/Courses/Show', [
            'course' => $course,
        ]);
    }

    public function edit(Course $course)
    {
        // Ensure instructor can only edit their own courses
        if ($course->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::active()->orderBy('name')->get();

        return inertia('Instructor/Courses/Edit', [
            'course' => $course,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Course $course)
    {
        // Ensure instructor can only update their own courses
        if ($course->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0|max:9999.99',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_hours' => 'nullable|integer|min:1|max:1000',
            'requirements' => 'nullable|array',
            'requirements.*' => 'string|max:255',
            'what_you_learn' => 'nullable|array',
            'what_you_learn.*' => 'string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            $this->fileService->deleteCourseThumbnail($course->thumbnail);
            
            // Upload new thumbnail
            $validated['thumbnail'] = $this->fileService->uploadCourseThumbnail($request->file('thumbnail'));
        }

        // Update slug if title changed
        if ($course->title !== $validated['title']) {
            $newSlug = Str::slug($validated['title']);
            $originalSlug = $newSlug;
            $counter = 1;
            while (Course::where('slug', $newSlug)->where('id', '!=', $course->id)->exists()) {
                $newSlug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $newSlug;
        }

        $course->update($validated);

        return redirect()->route('instructor.courses.show', $course)
                        ->with('success', 'Course updated successfully!');
    }

    public function destroy(Course $course)
    {
        // Ensure instructor can only delete their own courses
        if ($course->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete thumbnail if exists
        $this->fileService->deleteCourseThumbnail($course->thumbnail);


        $course->delete();

        return redirect()->route('instructor.courses.index')
                        ->with('success', 'Course deleted successfully!');
    }
}