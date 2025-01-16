<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\StorePasswordResetLinkRequest;
use App\Http\Controllers\Controller;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function __construct(protected FlasherInterface $flasher) {}

    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StorePasswordResetLinkRequest $request): RedirectResponse
    {

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            $this->flasher->addInfo($status);

            return redirect()->back();
        } else {
            return redirect()->back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
        }
    }
}
