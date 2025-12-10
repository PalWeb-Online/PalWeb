<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\AppServiceProvider;
use Flasher\Prime\FlasherInterface;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function __construct(protected FlasherInterface $flasher) {}

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
    public function link(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            session()->flash('notification', ['type' => 'info', 'message' => 'Your email is already verified.']);

            return back();
        }

        $request->user()->sendEmailVerificationNotification();
        session()->flash('notification', ['type' => 'success', 'message' => 'Verification link has been sent to your email!']);

        return back();
    }
}
