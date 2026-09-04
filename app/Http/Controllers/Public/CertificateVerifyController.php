<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Inertia\Inertia;
use Inertia\Response;

class CertificateVerifyController extends Controller
{
    public function __invoke(string $certificateNumber): Response
    {
        $certificate = Certificate::where('certificate_number', $certificateNumber)
            ->with([
                'user:id,name,email',
                'course' => fn ($q) => $q->with(['instructor:id,name', 'category:id,name']),
            ])
            ->first();

        return Inertia::render('Public/CertificateVerify', [
            'certificate' => $certificate,
            'searchedNumber' => $certificateNumber,
            'isValid' => ! is_null($certificate),
        ]);
    }
}
