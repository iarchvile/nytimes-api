<?php

namespace App\Listeners;

use App\Events\TooManyAttemptsPerDayEvent;
use Illuminate\Support\Facades\Log;

class LogTooManyAttemptsPerDay
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
        Log::warning('Too many requests detected');
    }
}
