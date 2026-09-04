<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CourseCatalogController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $filters = $request->only(['search', 'category', 'level', 'price', 'rating', 'sort']);

        $courses = Course::published()
            ->with(['instructor:id,name,avatar', 'category:id,name,slug'])
            ->withCount(['enrollments', 'lessons'])
            ->withAvg('reviews', 'rating')
            ->filter($filters)
            ->paginate(9)
            ->withQueryString();

        $categories = Category::where('is_active', true)
            ->withCount(['publishedCourses as courses_count'])
            ->get();

        return Inertia::render('Public/Courses/Index', [
            'courses' => $courses,
            'categories' => $categories,
            'filters' => $filters,
        ]);
    }
}
