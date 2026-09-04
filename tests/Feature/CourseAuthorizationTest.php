<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_instructor_cannot_edit_another_instructors_course(): void
    {
        $instructor1 = User::factory()->create(['role' => 'instructor']);
        $instructor2 = User::factory()->create(['role' => 'instructor']);
        $category = Category::create(['name' => 'Design', 'slug' => 'design', 'is_active' => true]);

        $course = Course::create([
            'instructor_id' => $instructor1->id,
            'category_id' => $category->id,
            'title' => 'Course 1',
            'slug' => 'course-1',
            'level' => 'beginner',
            'language' => 'English',
            'price' => 0,
            'status' => 'draft',
        ]);

        // Instructor 2 attempts to edit Instructor 1's course
        $response = $this->actingAs($instructor2)->get("/instructor/courses/{$course->id}/edit");
        $response->assertStatus(403);
    }

    public function test_student_cannot_access_admin_or_instructor_routes(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $responseAdmin = $this->actingAs($student)->get('/admin/dashboard');
        $responseAdmin->assertStatus(403);

        $responseInstructor = $this->actingAs($student)->get('/instructor/dashboard');
        $responseInstructor->assertStatus(403);
    }
}
