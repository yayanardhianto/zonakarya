<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class CacheHelper
{
    /**
     * Clear all application caches comprehensively
     * This ensures that all cached data is refreshed after settings update
     */
    public static function clearAllCaches()
    {
        // Clear application caches
        Cache::forget('setting');
        Cache::forget('seo_setting');
        Cache::forget('contact_section');
        Cache::forget('corn_working');
        
        // Clear config cache if exists
        if (Cache::has('config')) {
            Cache::forget('config');
        }
        
        // Clear Laravel caches
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        
        // Optional: Clear route cache if exists
        try {
            Artisan::call('route:clear');
        } catch (\Exception $e) {
            // Route cache might not exist, ignore error
        }
    }
    
    /**
     * Clear only setting-related caches
     */
    public static function clearSettingCaches()
    {
        Cache::forget('setting');
        Cache::forget('seo_setting');
        Cache::forget('contact_section');
        
        if (Cache::has('config')) {
            Cache::forget('config');
        }
    }
}
