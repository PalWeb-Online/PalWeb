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
     * Displays a view that prompts the user to verify their email.
     *
     * @return mixed
     */
    public function prompt(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(AppServiceProvider::HOME)
            : view('auth.verify-email');
    }

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
            return redirect()->intended(AppServiceProvider::HOME);
        }

        $request->user()->sendEmailVerificationNotification();

        $this->flasher->addInfo(__('verification.sent'));

        return redirect()->back();
    }
}
