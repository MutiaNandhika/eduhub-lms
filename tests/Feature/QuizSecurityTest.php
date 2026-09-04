<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_quiz_scoring_evaluated_on_server_and_grades_attempt_accurately(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $student = User::factory()->create(['role' => 'student']);
        $category = Category::create(['name' => 'Testing', 'slug' => 'testing', 'is_active' => true]);

        $course = Course::create([
            'instructor_id' => $instructor->id,
            'category_id' => $category->id,
            'title' => 'Test Course',
            'slug' => 'test-course',
            'level' => 'beginner',
            'language' => 'English',
            'price' => 0,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $module = CourseModule::create(['course_id' => $course->id, 'title' => 'Mod 1', 'sort_order' => 1]);
        $lesson = Lesson::create(['module_id' => $module->id, 'title' => 'Quiz Lesson', 'slug' => 'quiz-lesson', 'type' => 'quiz', 'sort_order' => 1]);

        $quiz = Quiz::create([
            'lesson_id' => $lesson->id,
            'title' => 'Assessment',
            'passing_score' => 50,
        ]);

        // Question 1
        $q1 = QuizQuestion::create(['quiz_id' => $quiz->id, 'question' => 'Q1', 'sort_order' => 1]);
        $opt1Correct = QuizOption::create(['question_id' => $q1->id, 'option_text' => 'Correct 1', 'is_correct' => true]);
        $opt1Wrong = QuizOption::create(['question_id' => $q1->id, 'option_text' => 'Wrong 1', 'is_correct' => false]);

        // Question 2
        $q2 = QuizQuestion::create(['quiz_id' => $quiz->id, 'question' => 'Q2', 'sort_order' => 2]);
        $opt2Correct = QuizOption::create(['question_id' => $q2->id, 'option_text' => 'Correct 2', 'is_correct' => true]);
        $opt2Wrong = QuizOption::create(['question_id' => $q2->id, 'option_text' => 'Wrong 2', 'is_correct' => false]);

        Enrollment::create(['user_id' => $student->id, 'course_id' => $course->id, 'status' => 'active']);

        // Submit 1 correct and 1 wrong answer (50% score -> passed)
        $response = $this->actingAs($student)->post("/quiz/{$quiz->id}/submit", [
            'answers' => [
                $q1->id => $opt1Correct->id,
                $q2->id => $opt2Wrong->id,
            ],
        ]);

        $this->assertDatabaseHas('quiz_attempts', [
            'quiz_id' => $quiz->id,
            'user_id' => $student->id,
            'score' => 50,
            'passed' => true,
        ]);

        // Lesson should now be automatically completed since quiz passed
        $this->assertDatabaseHas('lesson_progress', [
            'user_id' => $student->id,
            'lesson_id' => $lesson->id,
            'is_completed' => true,
        ]);
    }
}
