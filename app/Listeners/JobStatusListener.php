<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\JobStatus;

class JobStatusListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(JobStatus $event): void
    {
        if ($event->status == '[200]') {
            cache(['report'=>true]);
        }else{

        }
    }
}
