<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class QuizService
{
    public function __construct(
        protected CourseProgressService $progressService
    ) {}

    /**
     * Submit and evaluate a quiz attempt on the backend safely.
     *
     * @param array<int, int> $answers Map of question_id => selected option_id
     */
    public function submitQuiz(User $user, Quiz $quiz, array $answers): QuizAttempt
    {
        $lesson = $quiz->lesson;
        $course = $lesson->module->course;

        // Ensure user is enrolled or is admin/instructor
        if (! $user->isAdmin()) {
            $isEnrolled = Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->whereIn('status', ['active', 'completed'])
                ->exists();

            if (! $isEnrolled && $course->instructor_id !== $user->id) {
                throw ValidationException::withMessages([
                    'quiz' => 'You must be enrolled in this course to submit this quiz.',
                ]);
            }
        }

        $questions = $quiz->questions()->with('options')->get();
        $totalQuestions = $questions->count();

        if ($totalQuestions === 0) {
            throw ValidationException::withMessages([
                'quiz' => 'This quiz currently has no questions.',
            ]);
        }

        $correctCount = 0;
        $answerRecords = [];

        foreach ($questions as $question) {
            $selectedOptionId = $answers[$question->id] ?? null;
            $isCorrect = false;

            if ($selectedOptionId) {
                // Verify option belongs to this question
                $correctOption = $question->options->firstWhere('id', $selectedOptionId);
                if ($correctOption && $correctOption->is_correct) {
                    $isCorrect = true;
                    $correctCount++;
                }
            }

            $answerRecords[] = [
                'question_id' => $question->id,
                'option_id' => $selectedOptionId,
                'is_correct' => $isCorrect,
            ];
        }

        $score = (int) round(($correctCount / $totalQuestions) * 100);
        $passed = $score >= $quiz->passing_score;

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            'score' => $score,
            'passed' => $passed,
            'started_at' => now()->subMinutes(5),
            'completed_at' => now(),
        ]);

        foreach ($answerRecords as $record) {
            QuizAnswer::create([
                'attempt_id' => $attempt->id,
                'question_id' => $record['question_id'],
                'option_id' => $record['option_id'],
                'is_correct' => $record['is_correct'],
            ]);
        }

        // If passed, automatically mark the quiz lesson as complete
        if ($passed) {
            $this->progressService->markLessonComplete($user, $lesson, true);
        }

        return $attempt->load(['answers.question.options', 'answers.option', 'quiz']);
    }

    /**
     * Get quiz data for student player (hiding is_correct flag to prevent cheating).
     */
    public function getQuizForStudent(Quiz $quiz): array
    {
        $quiz->load(['lesson.module.course']);
        $questions = $quiz->questions()->with(['options' => function ($q) {
            $q->select('id', 'question_id', 'option_text'); // Never select is_correct!
        }])->get();

        return [
            'id' => $quiz->id,
            'title' => $quiz->title,
            'description' => $quiz->description,
            'passing_score' => $quiz->passing_score,
            'time_limit_minutes' => $quiz->time_limit_minutes,
            'lesson' => [
                'id' => $quiz->lesson->id,
                'title' => $quiz->lesson->title,
                'slug' => $quiz->lesson->slug,
                'module_id' => $quiz->lesson->module_id,
                'course' => [
                    'id' => $quiz->lesson->module->course->id,
                    'title' => $quiz->lesson->module->course->title,
                    'slug' => $quiz->lesson->module->course->slug,
                ],
            ],
            'questions' => $questions->map(function ($q) {
                return [
                    'id' => $q->id,
                    'question' => $q->question,
                    'sort_order' => $q->sort_order,
                    'options' => $q->options->map(fn ($opt) => [
                        'id' => $opt->id,
                        'option_text' => $opt->option_text,
                    ]),
                ];
            }),
        ];
    }
}
