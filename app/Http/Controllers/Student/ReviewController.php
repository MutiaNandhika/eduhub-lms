<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    public function store(Request $request, Course $course): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        // 1. Must be enrolled
        $isEnrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['active', 'completed'])
            ->exists();

        if (! $isEnrolled && ! $user->isAdmin()) {
            throw ValidationException::withMessages([
                'review' => 'You must be enrolled in this course to leave a review.',
            ]);
        }

        // 2. Prevent duplicates
        $existing = Review::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            throw ValidationException::withMessages([
                'review' => 'You have already reviewed this course. You can edit your existing review.',
            ]);
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:5', 'max:2000'],
        ]);

        Review::create([
            'course_id' => $course->id,
            'user_id' => $user->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Thank you for reviewing this course!');
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($review->user_id !== $user->id && ! $user->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:5', 'max:2000'],
        ]);

        $review->update($validated);

        return back()->with('success', 'Your review has been updated.');
    }

    public function destroy(Request $request, Review $review): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($review->user_id !== $user->id && ! $user->isAdmin()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Review removed successfully.');
    }
}
