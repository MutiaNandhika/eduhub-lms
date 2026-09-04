<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_render_dashboard(): void
    {
        $this->seed();
        $student = User::where('email', 'student@eduhub.test')->first();

        $response = $this->actingAs($student)->get('/dashboard');
        $response->assertOk();
    }

    public function test_student_can_render_my_courses(): void
    {
        $this->seed();
        $student = User::where('email', 'student@eduhub.test')->first();

        $response = $this->actingAs($student)->get('/my-courses');
        $response->assertOk();
    }

    public function test_student_can_render_certificates(): void
    {
        $this->seed();
        $student = User::where('email', 'student@eduhub.test')->first();

        $response = $this->actingAs($student)->get('/certificates');
        $response->assertOk();
    }
}
