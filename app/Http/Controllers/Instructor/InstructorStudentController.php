<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Services\CourseProgressService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InstructorStudentController extends Controller
{
    public function __construct(
        protected CourseProgressService $progressService
    ) {}

    public function __invoke(Request $request, Course $course): Response
    {
        $this->authorize('update', $course);

        $enrollments = Enrollment::where('course_id', $course->id)
            ->with(['user:id,name,email,avatar,created_at'])
            ->latest('enrolled_at')
            ->paginate(15);

        // Append progress percentage for each student
        $enrollments->getCollection()->transform(function ($enrollment) use ($course) {
            $progress = $this->progressService->getCourseProgress($enrollment->user, $course);
            $enrollment->progress = $progress;
            return $enrollment;
        });

        return Inertia::render('Instructor/Courses/Students', [
            'course' => $course,
            'enrollments' => $enrollments,
        ]);
    }
}
