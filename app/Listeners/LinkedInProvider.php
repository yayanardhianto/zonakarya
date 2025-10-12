<?php

namespace App\Listeners;

use SocialiteProviders\Manager\SocialiteWasCalled;

class LinkedInProvider
{
    /**
     * Handle the event.
     */
    public function handle(SocialiteWasCalled $event): void
    {
        $event->extendSocialite('linkedin', \SocialiteProviders\LinkedIn\Provider::class);
    }
}