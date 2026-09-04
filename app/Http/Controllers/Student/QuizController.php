<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Services\QuizService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class QuizController extends Controller
{
    public function __construct(
        protected QuizService $quizService
    ) {}

    public function show(Quiz $quiz): Response
    {
        $quizData = $this->quizService->getQuizForStudent($quiz);

        return Inertia::render('Student/Quiz/Show', [
            'quiz' => $quizData,
        ]);
    }

    public function submit(Request $request, Quiz $quiz): RedirectResponse
    {
        $request->validate([
            'answers' => ['required', 'array'],
            'answers.*' => ['nullable', 'integer'],
        ]);

        /** @var User $user */
        $user = $request->user();

        $attempt = $this->quizService->submitQuiz($user, $quiz, $request->input('answers'));

        return redirect()->route('student.quiz.result', [
            'quiz' => $quiz->id,
            'attempt' => $attempt->id,
        ])->with('success', $attempt->passed ? 'Congratulations! You passed the quiz.' : 'Quiz completed. Review your results below.');
    }

    public function result(Quiz $quiz, QuizAttempt $attempt): Response
    {
        $attempt->load([
            'quiz.lesson.module.course',
            'answers.question.options',
            'answers.option',
        ]);

        return Inertia::render('Student/Quiz/Result', [
            'quiz' => $quiz,
            'attempt' => $attempt,
        ]);
    }
}
