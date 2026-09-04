<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Services\EnrollmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function __construct(
        protected EnrollmentService $enrollmentService
    ) {}

    public function enroll(Request $request, Course $course): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->enrollmentService->enroll($user, $course);

        // Find first lesson in the course
        $firstLesson = $course->lessons()->first();

        if ($firstLesson) {
            return redirect()->route('student.learning', [
                'course' => $course->slug,
                'lesson' => $firstLesson->slug,
            ])->with('success', "Enrolled in {$course->title}! Let's start learning.");
        }

        return redirect()->route('courses.show', $course->slug)
            ->with('success', "Enrolled in {$course->title}!");
    }
}
