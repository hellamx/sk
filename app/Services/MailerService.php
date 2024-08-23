<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class MailerService
{
    /**
     * Send email.
     *
     * @param string $message
     * @param string $to
     * @param string $subject
     * @return void
     */
    public static function send(string $message, string $to, string $subject): void
    {
        Mail::html($message, function ($message) use ($to, $subject) {
            $message->to($to);
            $message->from(config('mail.from.address'));
            $message->subject($subject);
        });
    }
}
