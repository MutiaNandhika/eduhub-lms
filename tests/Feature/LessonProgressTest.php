<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonProgressTest extends TestCase
{
    use RefreshDatabase;

    public function test_lesson_completion_and_auto_course_completion_with_certificate(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $student = User::factory()->create(['role' => 'student']);
        $category = Category::create(['name' => 'Backend', 'slug' => 'backend', 'is_active' => true]);

        $course = Course::create([
            'instructor_id' => $instructor->id,
            'category_id' => $category->id,
            'title' => 'Laravel Mastery',
            'slug' => 'laravel-mastery',
            'short_description' => 'Course pitch',
            'level' => 'beginner',
            'language' => 'English',
            'price' => 0,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $module = CourseModule::create(['course_id' => $course->id, 'title' => 'Mod 1', 'sort_order' => 1]);
        $lesson1 = Lesson::create(['module_id' => $module->id, 'title' => 'L1', 'slug' => 'l1', 'type' => 'video', 'sort_order' => 1]);
        $lesson2 = Lesson::create(['module_id' => $module->id, 'title' => 'L2', 'slug' => 'l2', 'type' => 'article', 'sort_order' => 2]);

        $enrollment = Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'active',
        ]);

        // 1. Mark lesson 1 as complete
        $response1 = $this->actingAs($student)->post("/learning/{$lesson1->id}/toggle-complete");
        $this->assertDatabaseHas('lesson_progress', [
            'user_id' => $student->id,
            'lesson_id' => $lesson1->id,
            'is_completed' => true,
        ]);

        // Enrollment should still be active (50%)
        $this->assertEquals('active', $enrollment->fresh()->status);

        // 2. Mark lesson 2 as complete (100%)
        $response2 = $this->actingAs($student)->post("/learning/{$lesson2->id}/toggle-complete");
        $this->assertDatabaseHas('lesson_progress', [
            'user_id' => $student->id,
            'lesson_id' => $lesson2->id,
            'is_completed' => true,
        ]);

        // Enrollment should now be marked as completed
        $this->assertEquals('completed', $enrollment->fresh()->status);

        // Certificate should automatically exist
        $this->assertDatabaseHas('certificates', [
            'user_id' => $student->id,
            'course_id' => $course->id,
            'enrollment_id' => $enrollment->id,
        ]);
    }
}
