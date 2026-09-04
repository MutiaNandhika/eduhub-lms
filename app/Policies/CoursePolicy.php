<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Course $course): bool
    {
        if ($course->status === 'published') {
            return true;
        }

        if (! $user) {
            return false;
        }

        return $user->isAdmin() || $course->instructor_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isInstructor() || $user->isAdmin();
    }

    public function update(User $user, Course $course): bool
    {
        return $user->isAdmin() || $course->instructor_id === $user->id;
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->isAdmin() || ($course->instructor_id === $user->id && $course->enrollments()->count() === 0);
    }

    public function publish(User $user, Course $course): bool
    {
        return $user->isAdmin();
    }
}
