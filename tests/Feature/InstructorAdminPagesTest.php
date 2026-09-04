<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstructorAdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_instructor_pages_render_successfully(): void
    {
        $this->seed();
        $instructor = User::where('email', 'instructor@eduhub.test')->first();
        $course = Course::where('instructor_id', $instructor->id)->first();

        // 1. Dashboard
        $response = $this->actingAs($instructor)->get('/instructor/dashboard');
        $response->assertOk();

        // 2. Analytics
        $response = $this->actingAs($instructor)->get('/instructor/analytics');
        $response->assertOk();

        // 3. Courses List
        $response = $this->actingAs($instructor)->get('/instructor/courses');
        $response->assertOk();

        // 4. Course Create
        $response = $this->actingAs($instructor)->get('/instructor/courses/create');
        $response->assertOk();

        // 5. Course Edit / Studio
        if ($course) {
            $response = $this->actingAs($instructor)->get("/instructor/courses/{$course->id}/edit");
            $response->assertOk();

            // 6. Course Enrolled Students Tracker
            $response = $this->actingAs($instructor)->get("/instructor/courses/{$course->id}/students");
            $response->assertOk();
        }
    }

    public function test_admin_pages_render_successfully(): void
    {
        $this->seed();
        $admin = User::where('email', 'admin@eduhub.test')->first();

        // 1. Admin Dashboard
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertOk();

        // 2. Admin Analytics
        $response = $this->actingAs($admin)->get('/admin/analytics');
        $response->assertOk();

        // 3. Admin Users Management
        $response = $this->actingAs($admin)->get('/admin/users');
        $response->assertOk();

        // 4. Admin Courses Moderation
        $response = $this->actingAs($admin)->get('/admin/courses');
        $response->assertOk();

        // 5. Admin Categories Management
        $response = $this->actingAs($admin)->get('/admin/categories');
        $response->assertOk();

        // 6. Admin Reviews Moderation
        $response = $this->actingAs($admin)->get('/admin/reviews');
        $response->assertOk();
    }
}
