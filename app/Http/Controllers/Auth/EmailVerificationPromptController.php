<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View|JsonResponse
    {
        if ($request->wantsJson()) {
            return response()->json([
                'verified' => (bool) $request->user()?->hasVerifiedEmail(),
            ]);
        }

        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('dashboard', absolute: false))
                    : view('auth.verify-email');
    }

    /**
     * Check if the authenticated user's email has been verified.
     */
    public function status(Request $request): JsonResponse
    {
        return response()->json([
            'verified' => (bool) $request->user()?->hasVerifiedEmail(),
        ]);
    }
}
