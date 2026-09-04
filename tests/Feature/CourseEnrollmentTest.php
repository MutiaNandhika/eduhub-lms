<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    protected User $instructor;
    protected User $student;
    protected Category $category;
    protected Course $course;
    protected Lesson $lesson;

    protected function setUp(): void
    {
        parent::setUp();

        $this->instructor = User::factory()->create(['role' => 'instructor']);
        $this->student = User::factory()->create(['role' => 'student']);
        $this->category = Category::create([
            'name' => 'Web Dev',
            'slug' => 'web-dev',
            'is_active' => true,
        ]);

        $this->course = Course::create([
            'instructor_id' => $this->instructor->id,
            'category_id' => $this->category->id,
            'title' => 'Vue 3 Mastery',
            'slug' => 'vue-3-mastery',
            'short_description' => 'Course pitch',
            'level' => 'beginner',
            'language' => 'English',
            'price' => 0,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $module = CourseModule::create([
            'course_id' => $this->course->id,
            'title' => 'Module 1',
            'sort_order' => 1,
        ]);

        $this->lesson = Lesson::create([
            'module_id' => $module->id,
            'title' => 'Lesson 1',
            'slug' => 'lesson-1',
            'type' => 'video',
            'duration_minutes' => 10,
            'sort_order' => 1,
        ]);
    }

    public function test_student_can_enroll_in_course(): void
    {
        $response = $this->actingAs($this->student)->post("/courses/{$this->course->id}/enroll");

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);

        $response->assertRedirect("/learning/{$this->course->slug}/{$this->lesson->slug}");
    }

    public function test_duplicate_enrollment_is_prevented(): void
    {
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->student)->post("/courses/{$this->course->id}/enroll");

        $response->assertSessionHasErrors('course');
        $this->assertEquals(1, Enrollment::where('user_id', $this->student->id)->where('course_id', $this->course->id)->count());
    }

    public function test_instructor_cannot_enroll_in_own_course(): void
    {
        $response = $this->actingAs($this->instructor)->post("/courses/{$this->course->id}/enroll");

        $response->assertSessionHasErrors('course');
        $this->assertDatabaseMissing('enrollments', [
            'user_id' => $this->instructor->id,
            'course_id' => $this->course->id,
        ]);
    }
}
