<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class CourseBuilderController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function index(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        $courses = Course::where('instructor_id', $user->id)
            ->with(['category:id,name', 'modules.lessons'])
            ->withCount(['enrollments', 'lessons'])
            ->withAvg('reviews', 'rating')
            ->latest()
            ->paginate(10);

        return Inertia::render('Instructor/Courses/Index', [
            'courses' => $courses,
        ]);
    }

    public function create(): Response
    {
        $categories = Category::where('is_active', true)->get();

        return Inertia::render('Instructor/Courses/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'level' => ['required', 'in:beginner,intermediate,advanced'],
            'language' => ['required', 'string', 'max:50'],
            'short_description' => ['required', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'string'],
            'preview_video' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
        ]);

        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Course::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $course = Course::create([
            ...$validated,
            'instructor_id' => $user->id,
            'slug' => $slug,
            'status' => 'draft',
        ]);

        // Create default initial module
        CourseModule::create([
            'course_id' => $course->id,
            'title' => 'Module 1: Introduction & Overview',
            'sort_order' => 1,
        ]);

        return redirect()->route('instructor.courses.edit', $course->id)
            ->with('success', 'Course created! Now you can design your curriculum, lessons, and quizzes.');
    }

    public function edit(Course $course): Response
    {
        $this->authorize('update', $course);

        $course->load([
            'category',
            'modules' => fn ($q) => $q->orderBy('sort_order', 'asc')->with([
                'lessons' => fn ($l) => $l->orderBy('sort_order', 'asc')->with([
                    'resources',
                    'quiz.questions.options',
                ]),
            ]),
        ]);

        $categories = Category::where('is_active', true)->get();

        return Inertia::render('Instructor/Courses/Edit', [
            'course' => $course,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'level' => ['required', 'in:beginner,intermediate,advanced'],
            'language' => ['required', 'string', 'max:50'],
            'short_description' => ['required', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'string'],
            'preview_video' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
        ]);

        $course->update($validated);

        return back()->with('success', 'Course details updated successfully.');
    }

    public function submitForReview(Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        if ($course->lessons()->count() === 0) {
            return back()->with('error', 'Please add at least one lesson before submitting your course for review.');
        }

        $course->update(['status' => 'pending']);

        // Notify admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $this->notificationService->notify(
                $admin,
                'course_pending',
                'Course Submitted for Review',
                "Course '{$course->title}' was submitted by {$course->instructor->name} for review."
            );
        }

        return back()->with('success', 'Course submitted for admin review! You will be notified when it is approved.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $this->authorize('delete', $course);

        $course->delete();

        return redirect()->route('instructor.courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    // Module management
    public function storeModule(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $maxSort = $course->modules()->max('sort_order') ?? 0;

        $course->modules()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'sort_order' => $maxSort + 1,
        ]);

        return back()->with('success', 'Module added successfully.');
    }

    public function updateModule(Request $request, CourseModule $module): RedirectResponse
    {
        $this->authorize('update', $module->course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $module->update($validated);

        return back()->with('success', 'Module updated successfully.');
    }

    public function deleteModule(CourseModule $module): RedirectResponse
    {
        $this->authorize('update', $module->course);

        $module->delete();

        return back()->with('success', 'Module removed.');
    }

    // Lesson management
    public function storeLesson(Request $request, CourseModule $module): RedirectResponse
    {
        $this->authorize('update', $module->course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:video,article,quiz'],
            'video_url' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'is_preview' => ['nullable', 'boolean'],
        ]);

        $maxSort = $module->lessons()->max('sort_order') ?? 0;
        $slug = Str::slug($validated['title']) . '-' . Str::random(5);

        $lesson = $module->lessons()->create([
            'title' => $validated['title'],
            'slug' => $slug,
            'type' => $validated['type'],
            'video_url' => $validated['video_url'] ?? null,
            'content' => $validated['content'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? 0,
            'is_preview' => $request->boolean('is_preview'),
            'sort_order' => $maxSort + 1,
        ]);

        // If quiz, create empty initial quiz
        if ($lesson->type === 'quiz') {
            Quiz::create([
                'lesson_id' => $lesson->id,
                'title' => $lesson->title . ' Assessment',
                'passing_score' => 70,
            ]);
        }

        return back()->with('success', 'Lesson added to module.');
    }

    public function updateLesson(Request $request, Lesson $lesson): RedirectResponse
    {
        $this->authorize('update', $lesson->module->course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:video,article,quiz'],
            'video_url' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'is_preview' => ['nullable', 'boolean'],
        ]);

        $lesson->update([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'video_url' => $validated['video_url'] ?? null,
            'content' => $validated['content'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? 0,
            'is_preview' => $request->boolean('is_preview'),
        ]);

        if ($lesson->type === 'quiz' && ! $lesson->quiz) {
            Quiz::create([
                'lesson_id' => $lesson->id,
                'title' => $lesson->title . ' Assessment',
                'passing_score' => 70,
            ]);
        }

        return back()->with('success', 'Lesson updated.');
    }

    public function deleteLesson(Lesson $lesson): RedirectResponse
    {
        $this->authorize('update', $lesson->module->course);

        $lesson->delete();

        return back()->with('success', 'Lesson removed.');
    }

    // Quiz builder
    public function saveQuiz(Request $request, Quiz $quiz): RedirectResponse
    {
        $this->authorize('update', $quiz->lesson->module->course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'passing_score' => ['required', 'integer', 'min:1', 'max:100'],
            'time_limit_minutes' => ['nullable', 'integer', 'min:1'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.question' => ['required', 'string'],
            'questions.*.explanation' => ['nullable', 'string'],
            'questions.*.options' => ['required', 'array', 'min:2'],
            'questions.*.options.*.option_text' => ['required', 'string'],
            'questions.*.options.*.is_correct' => ['required', 'boolean'],
        ]);

        $quiz->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'passing_score' => $validated['passing_score'],
            'time_limit_minutes' => $validated['time_limit_minutes'] ?? null,
        ]);

        // Re-sync questions and options cleanly
        $quiz->questions()->delete();

        foreach ($validated['questions'] as $qIndex => $qData) {
            $question = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $qData['question'],
                'explanation' => $qData['explanation'] ?? null,
                'sort_order' => $qIndex + 1,
            ]);

            foreach ($qData['options'] as $optData) {
                QuizOption::create([
                    'question_id' => $question->id,
                    'option_text' => $optData['option_text'],
                    'is_correct' => (bool) $optData['is_correct'],
                ]);
            }
        }

        return back()->with('success', 'Quiz saved successfully with all questions.');
    }
}
