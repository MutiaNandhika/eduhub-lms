<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use App\Services\CourseProgressService;
use App\Services\EnrollmentService;
use App\Services\QuizService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LearningController extends Controller
{
    public function __construct(
        protected CourseProgressService $progressService,
        protected EnrollmentService $enrollmentService,
        protected QuizService $quizService
    ) {}

    public function show(Request $request, string $courseSlug, string $lessonSlug): Response|RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $course = Course::where('slug', $courseSlug)
            ->with([
                'modules' => fn ($q) => $q->orderBy('sort_order', 'asc')->with([
                    'lessons' => fn ($l) => $l->orderBy('sort_order', 'asc'),
                ]),
            ])
            ->firstOrFail();

        $currentLesson = Lesson::where('slug', $lessonSlug)
            ->whereHas('module', fn ($m) => $m->where('course_id', $course->id))
            ->with(['resources', 'quiz.questions.options'])
            ->firstOrFail();

        // Authorization check
        $isEnrolled = $this->enrollmentService->isEnrolled($user, $course);
        if (! $isEnrolled && ! $user->isAdmin() && $course->instructor_id !== $user->id && ! $currentLesson->is_preview) {
            return redirect()->route('courses.show', $course->slug)
                ->with('warning', 'Please enroll in this course to access this lesson.');
        }

        // Ordered lessons array
        $allLessons = $course->lessons()->get();
        $currentIndex = $allLessons->search(fn ($l) => $l->id === $currentLesson->id);

        $prevLesson = $currentIndex > 0 ? $allLessons->get($currentIndex - 1) : null;
        $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons->get($currentIndex + 1) : null;

        $progress = $this->progressService->getCourseProgress($user, $course);

        $isCompleted = LessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $currentLesson->id)
            ->where('is_completed', true)
            ->exists();

        // Sanitize quiz if lesson type is quiz
        $quizData = null;
        if ($currentLesson->type === 'quiz' && $currentLesson->quiz) {
            $quizData = $this->quizService->getQuizForStudent($currentLesson->quiz);
        }

        return Inertia::render('Student/Learning/Show', [
            'course' => $course,
            'currentLesson' => $currentLesson,
            'quiz' => $quizData,
            'progress' => $progress,
            'prevLesson' => $prevLesson,
            'nextLesson' => $nextLesson,
            'isCompleted' => $isCompleted,
        ]);
    }

    public function toggleComplete(Request $request, Lesson $lesson): JsonResponse|RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $currentProgress = LessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        $newStatus = ! ($currentProgress && $currentProgress->is_completed);

        $progressData = $this->progressService->markLessonComplete($user, $lesson, $newStatus);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_completed' => $newStatus,
                'progress' => $progressData,
            ]);
        }

        return back()->with('success', $newStatus ? 'Lesson marked as completed!' : 'Lesson marked as incomplete.');
    }

    public function updateProgress(Request $request, Lesson $lesson): JsonResponse
    {
        $validated = $request->validate([
            'progress_seconds' => ['required', 'integer', 'min:0'],
        ]);

        /** @var User $user */
        $user = $request->user();

        $this->progressService->updateVideoProgress($user, $lesson, $validated['progress_seconds']);

        return response()->json(['success' => true]);
    }
}
