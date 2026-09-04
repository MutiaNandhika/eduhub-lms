<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Services\CourseProgressService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        protected CourseProgressService $progressService
    ) {}

    public function __invoke(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        $enrollments = Enrollment::where('user_id', $user->id)
            ->with([
                'course' => fn ($q) => $q->with(['instructor:id,name,avatar', 'category:id,name,slug', 'modules.lessons'])
                    ->withCount('lessons'),
            ])
            ->latest('enrolled_at')
            ->get();

        $enrolledCoursesData = $enrollments->map(function ($enrollment) use ($user) {
            $course = $enrollment->course;
            if (! $course) return null;

            $progress = $this->progressService->getCourseProgress($user, $course);
            $course->progress = $progress;
            $course->enrollment = [
                'id' => $enrollment->id,
                'status' => $enrollment->status,
                'enrolled_at' => $enrollment->enrolled_at,
                'completed_at' => $enrollment->completed_at,
            ];

            return $course;
        })->filter()->values();

        $totalEnrolled = $enrollments->count();
        $completedCourses = $enrollments->where('status', 'completed')->count();
        $totalCertificates = Certificate::where('user_id', $user->id)->count();

        // Estimated learning minutes
        $completedLessonMinutes = LessonProgress::where('user_id', $user->id)
            ->where('is_completed', true)
            ->join('lessons', 'lesson_progress.lesson_id', '=', 'lessons.id')
            ->sum('lessons.duration_minutes');

        $learningHours = round($completedLessonMinutes / 60, 1);

        $continueCourse = $enrolledCoursesData->first(fn ($c) => ! ($c->progress['is_completed'] ?? false));

        $recentQuizzes = QuizAttempt::where('user_id', $user->id)
            ->with(['quiz.lesson.module.course:id,title,slug'])
            ->latest()
            ->take(5)
            ->get();

        $recentCertificates = Certificate::where('user_id', $user->id)
            ->with(['course:id,title,slug,thumbnail'])
            ->latest()
            ->take(4)
            ->get();

        $enrolledCategoryIds = $enrolledCoursesData->pluck('category_id')->unique()->toArray();
        $recommendedCourses = Course::published()
            ->whereNotIn('id', $enrolledCoursesData->pluck('id')->toArray())
            ->when(!empty($enrolledCategoryIds), fn ($q) => $q->whereIn('category_id', $enrolledCategoryIds))
            ->with(['instructor:id,name,avatar', 'category:id,name,slug'])
            ->withCount(['enrollments', 'lessons'])
            ->withAvg('reviews', 'rating')
            ->take(3)
            ->get();

        return Inertia::render('Student/Dashboard', [
            'stats' => [
                'total_enrolled' => $totalEnrolled,
                'completed_courses' => $completedCourses,
                'total_certificates' => $totalCertificates,
                'learning_hours' => $learningHours,
            ],
            'continueCourse' => $continueCourse,
            'enrolledCourses' => $enrolledCoursesData->take(4),
            'recommendedCourses' => $recommendedCourses,
            'recentQuizzes' => $recentQuizzes,
            'recentCertificates' => $recentCertificates,
        ]);
    }
}
