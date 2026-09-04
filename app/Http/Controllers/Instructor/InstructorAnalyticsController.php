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

class InstructorAnalyticsController extends Controller
{
    public function __invoke(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        $courses = Course::where('instructor_id', $user->id)
            ->withCount(['enrollments', 'lessons', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->get();

        $courseIds = $courses->pluck('id')->toArray();

        $enrollments = Enrollment::whereIn('course_id', $courseIds)
            ->with('course:id,title,price,discount_price')
            ->get();

        $totalRevenue = $enrollments->sum(function ($e) {
            $c = $e->course;
            if (! $c) return 0;
            return (float) ($c->discount_price ?? $c->price);
        });

        // Group enrollments by month (last 6 months)
        $monthlyEnrollments = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthLabel = $date->format('M Y');

            $count = $enrollments->filter(function ($e) use ($monthKey) {
                return $e->enrolled_at && $e->enrolled_at->format('Y-m') === $monthKey;
            })->count();

            $monthlyEnrollments[] = [
                'month' => $monthLabel,
                'enrollments' => $count,
            ];
        }

        // Ratings distribution (1 to 5 stars)
        $ratingDistribution = [];
        $reviews = Review::whereIn('course_id', $courseIds)->get();
        for ($star = 5; $star >= 1; $star--) {
            $count = $reviews->where('rating', $star)->count();
            $percentage = $reviews->count() > 0 ? round(($count / $reviews->count()) * 100) : 0;
            $ratingDistribution[] = [
                'stars' => $star,
                'count' => $count,
                'percentage' => $percentage,
            ];
        }

        return Inertia::render('Instructor/Analytics', [
            'courses' => $courses,
            'stats' => [
                'total_revenue' => round($totalRevenue, 2),
                'total_enrollments' => $enrollments->count(),
                'total_students' => $enrollments->pluck('user_id')->unique()->count(),
                'avg_rating' => round($reviews->avg('rating') ?? 5.0, 1),
            ],
            'monthlyEnrollments' => $monthlyEnrollments,
            'ratingDistribution' => $ratingDistribution,
        ]);
    }
}
