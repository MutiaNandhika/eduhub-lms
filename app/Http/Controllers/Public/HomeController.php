<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Review;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $featuredCourses = Course::published()
            ->with(['instructor:id,name,avatar', 'category:id,name,slug'])
            ->withCount(['enrollments', 'lessons'])
            ->withAvg('reviews', 'rating')
            ->orderBy('enrollments_count', 'desc')
            ->take(6)
            ->get();

        $categories = Category::where('is_active', true)
            ->withCount(['publishedCourses as courses_count'])
            ->orderBy('courses_count', 'desc')
            ->take(8)
            ->get();

        $stats = [
            'students' => User::where('role', 'student')->count(),
            'courses' => Course::published()->count(),
            'lessons' => Lesson::count(),
            'certificates' => Certificate::count(),
        ];

        $instructors = User::where('role', 'instructor')
            ->withCount('courses')
            ->take(4)
            ->get();

        $testimonials = Review::with(['user:id,name,avatar', 'course:id,title,slug'])
            ->where('rating', '>=', 4)
            ->whereNotNull('comment')
            ->latest()
            ->take(3)
            ->get();

        return Inertia::render('Public/Home', [
            'featuredCourses' => $featuredCourses,
            'categories' => $categories,
            'stats' => $stats,
            'instructors' => $instructors,
            'testimonials' => $testimonials,
        ]);
    }
}
