<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\User;
use App\Services\CourseProgressService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MyCoursesController extends Controller
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

        $courses = $enrollments->map(function ($enrollment) use ($user) {
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

            // Find last accessed lesson or first incomplete lesson
            $lastProgress = LessonProgress::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->latest('updated_at')
                ->first();

            $nextLesson = null;
            if ($lastProgress) {
                $nextLesson = $course->lessons()->where('lessons.id', $lastProgress->lesson_id)->first();
            }

            if (! $nextLesson) {
                $nextLesson = $course->lessons()->first();
            }

            $course->next_lesson = $nextLesson;

            return $course;
        })->filter()->values();

        return Inertia::render('Student/MyCourses', [
            'courses' => $courses,
        ]);
    }
}
