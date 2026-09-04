<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Review;
use App\Services\CourseProgressService;
use App\Services\EnrollmentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CourseDetailController extends Controller
{
    public function __construct(
        protected CourseProgressService $progressService,
        protected EnrollmentService $enrollmentService
    ) {}

    public function __invoke(Request $request, string $slug): Response
    {
        $course = Course::where('slug', $slug)
            ->with([
                'instructor:id,name,avatar,bio',
                'category:id,name,slug',
                'modules' => fn ($q) => $q->orderBy('sort_order', 'asc')->with([
                    'lessons' => fn ($l) => $l->orderBy('sort_order', 'asc'),
                ]),
                'reviews' => fn ($r) => $r->with('user:id,name,avatar')->latest(),
            ])
            ->withCount(['enrollments', 'lessons', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->firstOrFail();

        $user = $request->user();
        $isEnrolled = false;
        $progress = null;
        $userReview = null;
        $canReview = false;

        if ($user) {
            $isEnrolled = $this->enrollmentService->isEnrolled($user, $course);
            if ($isEnrolled) {
                $progress = $this->progressService->getCourseProgress($user, $course);
            }

            $userReview = Review::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->first();

            $canReview = $isEnrolled && ! $userReview;
        }

        $relatedCourses = Course::published()
            ->where('category_id', $course->category_id)
            ->where('id', '!=', $course->id)
            ->with(['instructor:id,name,avatar', 'category:id,name,slug'])
            ->withCount(['enrollments', 'lessons'])
            ->withAvg('reviews', 'rating')
            ->take(3)
            ->get();

        return Inertia::render('Public/Courses/Show', [
            'course' => $course,
            'isEnrolled' => $isEnrolled,
            'progress' => $progress,
            'userReview' => $userReview,
            'canReview' => $canReview,
            'relatedCourses' => $relatedCourses,
        ]);
    }
}
