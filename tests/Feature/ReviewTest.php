<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_enrolled_student_can_submit_review(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $student = User::factory()->create(['role' => 'student']);
        $category = Category::create(['name' => 'AI', 'slug' => 'ai', 'is_active' => true]);

        $course = Course::create([
            'instructor_id' => $instructor->id,
            'category_id' => $category->id,
            'title' => 'AI Engineering',
            'slug' => 'ai-engineering',
            'level' => 'beginner',
            'language' => 'English',
            'price' => 0,
            'status' => 'published',
            'published_at' => now(),
        ]);

        Enrollment::create(['user_id' => $student->id, 'course_id' => $course->id, 'status' => 'active']);

        $response = $this->actingAs($student)->post("/courses/{$course->id}/reviews", [
            'rating' => 5,
            'comment' => 'Exceptional content and practical exercises!',
        ]);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $student->id,
            'course_id' => $course->id,
            'rating' => 5,
        ]);
    }

    public function test_non_enrolled_user_cannot_review_course(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $student = User::factory()->create(['role' => 'student']);
        $category = Category::create(['name' => 'AI', 'slug' => 'ai', 'is_active' => true]);

        $course = Course::create([
            'instructor_id' => $instructor->id,
            'category_id' => $category->id,
            'title' => 'AI Engineering',
            'slug' => 'ai-engineering',
            'level' => 'beginner',
            'language' => 'English',
            'price' => 0,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->actingAs($student)->post("/courses/{$course->id}/reviews", [
            'rating' => 5,
            'comment' => 'I am not enrolled but trying to review',
        ]);

        $response->assertSessionHasErrors('review');
    }
}
