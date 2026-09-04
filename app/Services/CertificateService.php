<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Support\Str;

class CertificateService
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    /**
     * Generate unique certificate number.
     * Format: EDU-YYYY-XXXXXX (e.g. EDU-2026-000124)
     */
    public function generateCertificateNumber(): string
    {
        $year = date('Y');
        $count = Certificate::whereYear('created_at', $year)->count() + 1;
        $padded = str_pad((string) $count, 6, '0', STR_PAD_LEFT);
        $number = "EDU-{$year}-{$padded}";

        // Ensure absolute uniqueness
        while (Certificate::where('certificate_number', $number)->exists()) {
            $random = strtoupper(Str::random(6));
            $number = "EDU-{$year}-{$random}";
        }

        return $number;
    }

    /**
     * Issue a certificate for completed enrollment.
     */
    public function issueCertificate(User $user, Course $course, Enrollment $enrollment): Certificate
    {
        $existing = Certificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        $certificate = Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrollment_id' => $enrollment->id,
            'certificate_number' => $this->generateCertificateNumber(),
            'issued_at' => now(),
        ]);

        $this->notificationService->notify(
            $user,
            'certificate_issued',
            'Certificate Issued!',
            "Congratulations! Your certificate for '{$course->title}' has been generated ({$certificate->certificate_number})."
        );

        return $certificate;
    }
}
