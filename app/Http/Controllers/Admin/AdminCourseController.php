<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminCourseController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function index(Request $request): Response
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $courses = Course::query()
            ->when($search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhereHas('instructor', fn ($inst) => $inst->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($status && $status !== 'all', function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->with(['instructor:id,name,email,avatar', 'category:id,name'])
            ->withCount(['enrollments', 'lessons', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Admin/Courses/Index', [
            'courses' => $courses,
            'filters' => [
                'search' => $search,
                'status' => $status,
            ],
        ]);
    }

    public function approve(Course $course): RedirectResponse
    {
        $course->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->notificationService->notify(
            $course->instructor,
            'course_approved',
            'Course Approved & Published!',
            "Great news! Your course '{$course->title}' has been approved and is now live on EduHub."
        );

        return back()->with('success', "Course '{$course->title}' was approved and published!");
    }

    public function reject(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $reason = $validated['reason'] ?? 'Needs revisions according to EduHub curriculum standards.';

        $course->update(['status' => 'draft']);

        $this->notificationService->notify(
            $course->instructor,
            'course_rejected',
            'Course Revisions Requested',
            "Your course '{$course->title}' was reviewed. Reason: {$reason}"
        );

        return back()->with('info', "Course '{$course->title}' was returned to draft status.");
    }

    public function archive(Course $course): RedirectResponse
    {
        $course->update(['status' => 'archived']);

        return back()->with('success', "Course '{$course->title}' has been archived.");
    }
}
