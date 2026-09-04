<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    public function create(User $user, Course $course): bool
    {
        // Must be enrolled
        $isEnrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['active', 'completed'])
            ->exists();

        if (! $isEnrolled) {
            return false;
        }

        // Cannot review twice
        return ! Review::where('user_id', $user->id)->where('course_id', $course->id)->exists();
    }

    public function update(User $user, Review $review): bool
    {
        return $review->user_id === $user->id;
    }

    public function delete(User $user, Review $review): bool
    {
        return $user->isAdmin() || $review->user_id === $user->id;
    }
}
