<?php

use App\Models\Admin;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Modules\Language\app\Models\Language;
use Modules\Marquee\app\Models\NewsTicker;
use Modules\GlobalSetting\app\Models\Setting;
use Modules\SocialLink\app\Models\SocialLink;
use Modules\GlobalSetting\app\Models\CustomCode;
use App\Exceptions\AccessPermissionDeniedException;
use Modules\PageBuilder\app\Models\CustomizeablePage;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

function file_upload(UploadedFile $file, string $path = 'uploads/custom-images/', string | null $oldFile = '', bool $optimize = false) {
    $extention = $file->getClientOriginalExtension();
    $file_name = 'wsus-img' . date('-Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $extention;
    $file->move($path, $file_name);
    if ($optimize) {
        ImageOptimizer::optimize($path . $file_name);
    }
    try {
        if ($oldFile && !str($oldFile)->contains('uploads/website-images') && File::exists(public_path($oldFile))) {
            unlink(public_path($oldFile));
        }
    } catch (Exception $e) {
        Log::error($e->getMessage());
    }
    return $path . $file_name;
}

if (!function_exists('allLanguages')) {
    function allLanguages() {
        if (!Cache::has('languages')) {
            Cache::rememberForever('languages', function () {
                try {
                    return Language::all();
                } catch (\Exception $e) {
                    // Return empty collection if table doesn't exist or error occurs
                    return collect();
                }
            });
        }
        return Cache::get('languages', collect());
    }
}

if (!function_exists('getSessionLanguage')) {
    function getSessionLanguage(): string {
        if (!session()->has('language_code')) {
            $language = allLanguages()->where('is_default', 1)->first();
            if ($language) {
                session()->put('language_code', $language->language_code);
                session()->put('language_name', $language->language_name);
                session()->put('language_direction', $language->language_direction);
            } else {
                // Fallback to 'en' if no default language found
                session()->put('language_code', 'en');
                session()->put('language_name', 'English');
                session()->put('language_direction', 'ltr');
            }
        }
        return Session::get('language_code') ?? 'en';
    }
}

if (!function_exists('setLanguage')) {
    function setLanguage($code) {
        $language = allLanguages()->where('language_code', $code)->first();
        if ($language) {
            session()->put('language_code', $language->language_code);
            session()->put('language_name', $language->language_name);
            session()->put('language_direction', $language->language_direction);
        }
    }
}

if (!function_exists('sessionForgetLangChang')) {
    function sessionForgetLangChang() {
        session()->forget('language_code');
        session()->forget('language_name');
        session()->forget('language_direction');
    }
}

function admin_lang() {
    return Session::get('admin_lang');
}

function html_decode($text) {
    $after_decode = htmlspecialchars_decode($text, ENT_QUOTES);
    return $after_decode;
}

if (!function_exists('currectUrlWithQuery')) {
    function currectUrlWithQuery($code) {
        $currentUrl = request()->url();
        $queryParams = request()->query();
        $queryParams['lang'] = $code;
        $queryString = http_build_query($queryParams);
        return $currentUrl . '?' . $queryString;
    }
}

if (!function_exists('checkAdminHasPermission')) {
    function checkAdminHasPermission($permission): bool {
        return Auth::guard('admin')->user()->can($permission);
    }
}

if (!function_exists('checkAdminHasPermissionAndThrowException')) {
    function checkAdminHasPermissionAndThrowException($permission) {
        if (!checkAdminHasPermission($permission)) {
            throw new AccessPermissionDeniedException();
        }
    }
}

if (!function_exists('getSettingStatus')) {
    function getSettingStatus($key) {
        if (!Cache::has('setting')) {
            Cache::rememberForever('setting', function () {
                return Setting::first();
            });
        }
        $setting = Cache::get('setting');
        return $setting->$key ?? null;
    }
}

if (!function_exists('checkCrentials')) {
    function checkCrentials() {
        $notifications = [];
        
        
        return $notifications;
    }
}

if (!function_exists('isRoute')) {
    function isRoute(string | array $route, string $returnValue = null) {
        if (is_array($route)) {
            $isActive = false;
            foreach ($route as $r) {
                if (Route::is($r)) {
                    $isActive = true;
                    break;
                }
            }
            return $isActive ? ($returnValue ?? 'active') : '';
        }
        return Route::is($route) ? ($returnValue ?? 'active') : '';
    }
}

if (!function_exists('customCode')) {
    function customCode() {
        if (!Cache::has('custom_code')) {
            Cache::rememberForever('custom_code', function () {
                return CustomCode::first();
        });
        }
        return Cache::get('custom_code');
    }
}

if (!function_exists('customPages')) {
    function customPages() {
        return CustomizeablePage::active()->get();
    }
}

if (!function_exists('marquees')) {
    function marquees() {
        if (!Cache::has('marquees')) {
            Cache::rememberForever('marquees', function () {
                return NewsTicker::active()->get();
            });
        }
        return Cache::get('marquees');
    }
}

if (!function_exists('socialLinks')) {
    function socialLinks() {
        if (!Cache::has('social_links')) {
            Cache::rememberForever('social_links', function () {
                return SocialLink::all();
        });
        }
        return Cache::get('social_links');
    }
}

if (!function_exists('processText')) {
    function processText($text) {
        $text = html_decode($text);
        $text = replaceImageSources($text);
        return $text;
    }
}

if (!function_exists('userAuth')) {
    function userAuth() {
        return Auth::guard('web')->user();
    }
}

if (!function_exists('deleteUnusedUploadedImages')) {
    function deleteUnusedUploadedImages($html, $uploadPath = TINYMNCE_UPLOAD_PATH) {
        preg_match_all('/<img[^>]+src="([^"]+)"/', $html, $matches);
        $usedImages = $matches[1];
        $uploadDir = public_path($uploadPath);
        if (is_dir($uploadDir)) {
            $files = glob($uploadDir . '*');
            foreach ($files as $file) {
                $relativePath = str_replace(public_path(), '', $file);
                if (!in_array($relativePath, $usedImages)) {
                    unlink($file);
                }
            }
        }
    }
}

if (!function_exists('replaceImageSources')) {
    function replaceImageSources($html, $uploadPath = TINYMNCE_UPLOAD_PATH) {
        $pattern = '/<img([^>]+)src="([^"]+)"/';
        return preg_replace_callback($pattern, function ($matches) use ($uploadPath) {
            $attributes = $matches[1];
            $src = $matches[2];
            if (!str_starts_with($src, 'http') && !str_starts_with($src, '/')) {
                $src = asset($uploadPath . $src);
            }
            return '<img' . $attributes . 'src="' . $src . '"';
        }, $html);
    }
}

if (!function_exists('sessionLogoutAllDevice')) {
    /**
     * Logout user from all devices
     */
    function sessionLogoutAllDevice($user) {
        $user->tokens()->delete();
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
    }
}

if (!function_exists('server_max_upload_size')) {
    function server_max_upload_size() {
        $max_upload = convertPHPSizeToBytes(ini_get('upload_max_filesize'));
        $max_post = convertPHPSizeToBytes(ini_get('post_max_size'));
        $memory_limit = convertPHPSizeToBytes(ini_get('memory_limit'));
        return min($max_upload, $max_post, $memory_limit);
    }
}

if (!function_exists('convertPHPSizeToBytes')) {
    function convertPHPSizeToBytes($size) {
        $size = trim($size);
        $last = strtolower($size[strlen($size) - 1]);
        $size = (int) $size;
        switch ($last) {
            case 'g':
                $size *= 1024;
            case 'm':
                $size *= 1024;
            case 'k':
                $size *= 1024;
        }
        return $size;
    }
}
