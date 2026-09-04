<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;

class CourseProgressService
{
    public function __construct(
        protected CertificateService $certificateService,
        protected NotificationService $notificationService
    ) {}

    /**
     * Get learning progress stats for a course and student.
     */
    public function getCourseProgress(User $user, Course $course): array
    {
        $allLessons = $course->lessons()->get();
        $totalLessons = $allLessons->count();

        if ($totalLessons === 0) {
            return [
                'total_lessons' => 0,
                'completed_lessons' => 0,
                'progress_percentage' => 0,
                'completed_lesson_ids' => [],
                'is_completed' => false,
            ];
        }

        $completedProgress = LessonProgress::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('is_completed', true)
            ->get();

        $completedLessonIds = $completedProgress->pluck('lesson_id')->toArray();
        $completedLessonsCount = count($completedLessonIds);

        $percentage = min(100, (int) round(($completedLessonsCount / $totalLessons) * 100));

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        return [
            'total_lessons' => $totalLessons,
            'completed_lessons' => $completedLessonsCount,
            'progress_percentage' => $percentage,
            'completed_lesson_ids' => $completedLessonIds,
            'is_completed' => $enrollment ? $enrollment->isCompleted() : false,
        ];
    }

    /**
     * Mark a lesson as completed (or incomplete) for a user.
     */
    public function markLessonComplete(User $user, Lesson $lesson, bool $completed = true): array
    {
        $course = $lesson->module->course;

        $progress = LessonProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'course_id' => $course->id,
                'is_completed' => $completed,
                'completed_at' => $completed ? now() : null,
            ]
        );

        // Check if all lessons are completed to auto-complete the course
        $this->checkAndCompleteCourse($user, $course);

        return $this->getCourseProgress($user, $course);
    }

    /**
     * Save seconds watched for a video lesson.
     */
    public function updateVideoProgress(User $user, Lesson $lesson, int $seconds): LessonProgress
    {
        $course = $lesson->module->course;

        return LessonProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'course_id' => $course->id,
                'progress_seconds' => $seconds,
            ]
        );
    }

    /**
     * Check if all lessons in the course are completed and trigger completion & certificate.
     */
    public function checkAndCompleteCourse(User $user, Course $course): bool
    {
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (! $enrollment) {
            return false;
        }

        $allLessons = $course->lessons()->get();
        $totalLessons = $allLessons->count();

        if ($totalLessons === 0) {
            return false;
        }

        $completedCount = LessonProgress::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('is_completed', true)
            ->count();

        if ($completedCount >= $totalLessons) {
            if ($enrollment->status !== 'completed') {
                $enrollment->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);

                // Issue certificate automatically
                $this->certificateService->issueCertificate($user, $course, $enrollment);

                $this->notificationService->notify(
                    $user,
                    'course_completed',
                    'Course Completed!',
                    "Awesome job! You have successfully completed the course '{$course->title}'."
                );
            }
            return true;
        }

        return false;
    }
}
