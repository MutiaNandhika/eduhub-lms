<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CertificateController extends Controller
{
    public function index(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        $certificates = Certificate::where('user_id', $user->id)
            ->with(['course.instructor', 'course.category', 'user'])
            ->latest('issued_at')
            ->get();

        return Inertia::render('Student/Certificates/Index', [
            'certificates' => $certificates,
        ]);
    }

    public function show(Request $request, Certificate $certificate): Response
    {
        $certificate->load([
            'course.instructor',
            'course.category',
            'user',
        ]);

        return Inertia::render('Student/Certificates/Show', [
            'certificate' => $certificate,
        ]);
    }
}
