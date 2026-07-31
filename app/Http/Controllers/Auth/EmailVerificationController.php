<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\AppServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    /**
     * Mark the user's email address as verified.
     */
    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(AppServiceProvider::HOME.'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(AppServiceProvider::HOME.'?verified=1');
    }

    /**
     * Sends a new verification link to the user's email.
     */
    public function link(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'outcome' => 'already_verified',
            ]);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'outcome' => 'sent',
        ]);
    }
}
