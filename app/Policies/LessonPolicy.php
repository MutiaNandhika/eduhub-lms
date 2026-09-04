<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;

class LessonPolicy
{
    public function view(User $user, Lesson $lesson): bool
    {
        $course = $lesson->module->course;

        if ($user->isAdmin() || $course->instructor_id === $user->id) {
            return true;
        }

        if ($lesson->is_preview) {
            return true;
        }

        return Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['active', 'completed'])
            ->exists();
    }

    public function update(User $user, Lesson $lesson): bool
    {
        $course = $lesson->module->course;
        return $user->isAdmin() || $course->instructor_id === $user->id;
    }

    public function delete(User $user, Lesson $lesson): bool
    {
        $course = $lesson->module->course;
        return $user->isAdmin() || $course->instructor_id === $user->id;
    }
}
