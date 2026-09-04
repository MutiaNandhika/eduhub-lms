<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Review;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    public function __invoke(): Response
    {
        $totalUsers = User::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalInstructors = User::where('role', 'instructor')->count();
        $totalCourses = Course::count();
        $publishedCourses = Course::where('status', 'published')->count();
        $pendingCoursesCount = Course::where('status', 'pending')->count();
        $totalCertificates = Certificate::count();
        $totalEnrollments = Enrollment::count();

        $enrollments = Enrollment::with('course:id,price,discount_price')->get();
        $estimatedRevenue = $enrollments->sum(function ($e) {
            $c = $e->course;
            if (! $c) return 0;
            return (float) ($c->discount_price ?? $c->price);
        });

        // Pending courses requiring moderation
        $pendingCourses = Course::where('status', 'pending')
            ->with(['instructor:id,name,email,avatar', 'category:id,name'])
            ->withCount('lessons')
            ->latest()
            ->take(5)
            ->get();

        // Recent users
        $recentUsers = User::latest()->take(6)->get();

        // Recent enrollments
        $recentEnrollments = Enrollment::with(['user:id,name,avatar', 'course:id,title,slug'])
            ->latest('enrolled_at')
            ->take(6)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_users' => $totalUsers,
                'total_students' => $totalStudents,
                'total_instructors' => $totalInstructors,
                'total_courses' => $totalCourses,
                'published_courses' => $publishedCourses,
                'pending_courses' => $pendingCoursesCount,
                'total_certificates' => $totalCertificates,
                'total_enrollments' => $totalEnrollments,
                'estimated_revenue' => round($estimatedRevenue, 2),
            ],
            'pendingCourses' => $pendingCourses,
            'recentUsers' => $recentUsers,
            'recentEnrollments' => $recentEnrollments,
        ]);
    }
}
