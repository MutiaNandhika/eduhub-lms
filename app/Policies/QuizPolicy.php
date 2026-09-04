<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\User;

class QuizPolicy
{
    public function view(User $user, Quiz $quiz): bool
    {
        $course = $quiz->lesson->module->course;

        if ($user->isAdmin() || $course->instructor_id === $user->id) {
            return true;
        }

        return Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['active', 'completed'])
            ->exists();
    }

    public function submit(User $user, Quiz $quiz): bool
    {
        return $this->view($user, $quiz);
    }
}
