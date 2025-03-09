<?php

namespace App\Listeners;

use App\Events\TooManyAttemptsPerDayEvent;
use App\Notifications\TooManyAttemptsNotification;
use Illuminate\Support\Facades\Notification;

class NotifyTooManyAttemptsPerDay
{

    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(TooManyAttemptsPerDayEvent $event): void
    {
        Notification::route('mail', 'admin@example.com')
            ->notify(new TooManyAttemptsNotification($event));
    }
}
