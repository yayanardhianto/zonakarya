<?php

namespace App\Traits;

use App\Enums\SocialiteDriverType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

trait SetConfigTrait
{
    protected static function setGoogleLoginInfo()
    {
        $setting = Cache::get('setting');
        if ($setting) {
            Config::set('services.google.client_id', $setting->gmail_client_id);
            Config::set('services.google.client_secret', $setting->gmail_secret_id);
            Config::set('services.google.redirect', route('auth.google.callback'));
        }
    }

    protected static function setLinkedInLoginInfo()
    {
        $setting = Cache::get('setting');
        if ($setting) {
            Config::set('services.linkedin.client_id', $setting->linkedin_client_id);
            Config::set('services.linkedin.client_secret', $setting->linkedin_client_secret);
            Config::set('services.linkedin.redirect', route('auth.linkedin.callback'));
        }
    }
}
