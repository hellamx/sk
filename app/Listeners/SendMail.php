<?php

namespace App\Listeners;

use App\Events\ProductUpdated;
use App\Services\MailerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendMail
{
    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        $view = get_class($event) === 'App\Events\ProductCreated' ? 'created' : 'updated';
        $subject = $view === 'created' ? 'New product' : 'Product updated';

        MailerService::send(view('mails.product.' . $view, [
            'product' => $event->product
        ])->render(), config('mail.product_listener_email'), $subject);
    }
}
