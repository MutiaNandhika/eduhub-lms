<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;

class EnrollmentPolicy
{
    public function view(User $user, Enrollment $enrollment): bool
    {
        return $user->isAdmin() || $enrollment->user_id === $user->id || $enrollment->course->instructor_id === $user->id;
    }
}
