<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class AdminAnalyticsController extends Controller
{
    public function __invoke(): Response
    {
        $courses = Course::withCount(['enrollments', 'reviews'])->get();
        $enrollments = Enrollment::with('course:id,price,discount_price')->get();

        $totalRevenue = $enrollments->sum(function ($e) {
            $c = $e->course;
            if (! $c) return 0;
            return (float) ($c->discount_price ?? $c->price);
        });

        // Top categories by course count and enrollments
        $categories = Category::withCount(['courses', 'publishedCourses'])
            ->with(['courses.enrollments'])
            ->get()
            ->map(function ($cat) {
                $enrollmentsCount = $cat->courses->sum(fn ($c) => $c->enrollments->count());
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'courses_count' => $cat->courses_count,
                    'enrollments_count' => $enrollmentsCount,
                ];
            })
            ->sortByDesc('enrollments_count')
            ->values();

        // Top instructors
        $topInstructors = User::where('role', 'instructor')
            ->with(['courses.enrollments'])
            ->get()
            ->map(function ($inst) {
                $coursesCount = $inst->courses->count();
                $studentsCount = $inst->courses->flatMap(fn ($c) => $c->enrollments)->pluck('user_id')->unique()->count();
                return [
                    'id' => $inst->id,
                    'name' => $inst->name,
                    'email' => $inst->email,
                    'avatar' => $inst->avatar,
                    'courses_count' => $coursesCount,
                    'students_count' => $studentsCount,
                ];
            })
            ->sortByDesc('students_count')
            ->take(5)
            ->values();

        // Monthly registrations & enrollments
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthLabel = $date->format('M Y');

            $userCount = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $enrollCount = Enrollment::whereYear('enrolled_at', $date->year)
                ->whereMonth('enrolled_at', $date->month)
                ->count();

            $certCount = Certificate::whereYear('issued_at', $date->year)
                ->whereMonth('issued_at', $date->month)
                ->count();

            $monthlyData[] = [
                'month' => $monthLabel,
                'users' => $userCount,
                'enrollments' => $enrollCount,
                'certificates' => $certCount,
            ];
        }

        return Inertia::render('Admin/Analytics', [
            'stats' => [
                'total_revenue' => round($totalRevenue, 2),
                'total_users' => User::count(),
                'total_courses' => Course::count(),
                'total_certificates' => Certificate::count(),
            ],
            'categories' => $categories,
            'topInstructors' => $topInstructors,
            'monthlyData' => $monthlyData,
        ]);
    }
}
