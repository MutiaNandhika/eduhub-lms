<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InstructorDashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        $courses = Course::where('instructor_id', $user->id)->get();
        $courseIds = $courses->pluck('id')->toArray();

        $totalCourses = $courses->count();
        $publishedCourses = $courses->where('status', 'published')->count();
        $pendingCourses = $courses->where('status', 'pending')->count();
        $draftCourses = $courses->where('status', 'draft')->count();

        $enrollments = Enrollment::whereIn('course_id', $courseIds)
            ->with(['user:id,name,email,avatar', 'course:id,title,price,discount_price'])
            ->latest('enrolled_at')
            ->get();

        $totalStudents = $enrollments->pluck('user_id')->unique()->count();
        $totalEnrollments = $enrollments->count();

        // Estimated revenue calculation
        $estimatedRevenue = $enrollments->sum(function ($e) {
            $c = $e->course;
            if (! $c) return 0;
            return (float) ($c->discount_price ?? $c->price);
        });

        // Average rating across all courses
        $avgRating = Review::whereIn('course_id', $courseIds)->avg('rating') ?? 5.0;
        $totalReviews = Review::whereIn('course_id', $courseIds)->count();

        // Top performing courses
        $topCourses = Course::where('instructor_id', $user->id)
            ->withCount(['enrollments', 'lessons'])
            ->withAvg('reviews', 'rating')
            ->orderBy('enrollments_count', 'desc')
            ->take(5)
            ->get();

        return Inertia::render('Instructor/Dashboard', [
            'stats' => [
                'total_courses' => $totalCourses,
                'published_courses' => $publishedCourses,
                'pending_courses' => $pendingCourses,
                'draft_courses' => $draftCourses,
                'total_students' => $totalStudents,
                'total_enrollments' => $totalEnrollments,
                'estimated_revenue' => round($estimatedRevenue, 2),
                'average_rating' => round($avgRating, 1),
                'total_reviews' => $totalReviews,
            ],
            'recentEnrollments' => $enrollments->take(6),
            'topCourses' => $topCourses,
        ]);
    }
}
