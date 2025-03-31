<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmailAnnouncementRequest;
use App\Mail\AnnouncementEmail;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailAnnouncementController extends Controller
{
    public function store(StoreEmailAnnouncementRequest $request): RedirectResponse
    {
        $subject = $request->input('subject');
        $body = $request->input('body');

        $users = User::all();

        foreach ($users as $user) {
            try {
                Mail::to($user->email)->queue(new AnnouncementEmail($subject, $body, $user));
            } catch (Exception $e) {
                Log::error('Failed to send email to '.$user->email.'. Error: '.$e->getMessage());
            }
        }

        session()->flash('notification', ['type' => 'success', 'message' => 'Mail has been queued!']);

        return to_route('homepage');
    }
}
