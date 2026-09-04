<?php

namespace App\Policies;

use App\Models\Certificate;
use App\Models\User;

class CertificatePolicy
{
    public function view(User $user, Certificate $certificate): bool
    {
        return $user->isAdmin() || $certificate->user_id === $user->id || $certificate->course->instructor_id === $user->id;
    }
}
