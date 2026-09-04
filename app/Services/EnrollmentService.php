<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class EnrollmentService
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    /**
     * Enroll a student into a course.
     */
    public function enroll(User $user, Course $course): Enrollment
    {
        // 1. Check if course is published
        if ($course->status !== 'published') {
            throw ValidationException::withMessages([
                'course' => 'This course is not currently available for enrollment.',
            ]);
        }

        // 2. Check if instructor is attempting to enroll in their own course
        if ($course->instructor_id === $user->id) {
            throw ValidationException::withMessages([
                'course' => 'You cannot enroll in a course you created.',
            ]);
        }

        // 3. Check if already enrolled
        $existing = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            if ($existing->status === 'cancelled') {
                $existing->update(['status' => 'active', 'enrolled_at' => now()]);
                return $existing;
            }

            throw ValidationException::withMessages([
                'course' => 'You are already enrolled in this course.',
            ]);
        }

        // 4. Create enrollment
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        // 5. Notify student
        $this->notificationService->notify(
            $user,
            'course_enrolled',
            'Enrolled Successfully!',
            "Welcome to '{$course->title}'! You can start learning right away."
        );

        return $enrollment;
    }

    /**
     * Check if user is enrolled.
     */
    public function isEnrolled(User $user, Course $course): bool
    {
        return Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['active', 'completed'])
            ->exists();
    }
}
