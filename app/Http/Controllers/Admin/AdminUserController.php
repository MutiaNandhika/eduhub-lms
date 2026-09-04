<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminUserController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->input('search');
        $role = $request->input('role');

        $users = User::query()
            ->when($search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role && $role !== 'all', function ($q) use ($role) {
                $q->where('role', $role);
            })
            ->withCount(['courses', 'enrollments', 'certificates'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => [
                'search' => $search,
                'role' => $role,
            ],
        ]);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        /** @var User $currentUser */
        $currentUser = $request->user();

        $validated = $request->validate([
            'role' => ['required', 'in:admin,instructor,student'],
        ]);

        // Self-demotion protection
        if ($user->id === $currentUser->id && $validated['role'] !== 'admin') {
            return back()->with('error', 'You cannot remove administrative privileges from your own account.');
        }

        $user->update(['role' => $validated['role']]);

        return back()->with('success', "Role for {$user->name} updated to {$validated['role']}.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        /** @var User $currentUser */
        $currentUser = $request->user();

        if ($user->id === $currentUser->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', "User {$user->name} was deleted.");
    }
}
