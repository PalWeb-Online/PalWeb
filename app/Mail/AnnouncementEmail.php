<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AnnouncementEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;

    public $body;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $body, User $user)
    {
        $this->subject = $subject;
        $this->body = nl2br(e($body));
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->view('mail.announcement')
            ->with([
                'subject' => $this->subject,
                'body' => $this->body,
                'user' => $this->user,
            ]);
    }
}
