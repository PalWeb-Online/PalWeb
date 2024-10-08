<?php

namespace App\Http\Controllers;

use App\Mail\AnnouncementEmail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class EmailAnnouncementController extends Controller
{

    public function compose()
    {
        View::share('pageTitle', 'Compose Email');
        return view('users.dashboard.email-compose');
    }

    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'body' => 'required',
        ]);

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

        // Redirect back with a success message
        return redirect('/')->with('success', 'Announcement email has been queued.');
    }
}
