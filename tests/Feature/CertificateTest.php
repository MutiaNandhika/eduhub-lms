<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CertificateTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_certificate_verification_returns_certificate_details(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $student = User::factory()->create(['role' => 'student']);
        $category = Category::create(['name' => 'Data', 'slug' => 'data', 'is_active' => true]);

        $course = Course::create([
            'instructor_id' => $instructor->id,
            'category_id' => $category->id,
            'title' => 'Python Data Science',
            'slug' => 'python-data-science',
            'level' => 'beginner',
            'language' => 'English',
            'price' => 0,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'completed',
        ]);

        $certificate = Certificate::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'enrollment_id' => $enrollment->id,
            'certificate_number' => 'EDU-2026-000999',
            'issued_at' => now(),
        ]);

        $response = $this->get("/verify/{$certificate->certificate_number}");
        $response->assertStatus(200);
        $response->assertSee('EDU-2026-000999');
    }
}
