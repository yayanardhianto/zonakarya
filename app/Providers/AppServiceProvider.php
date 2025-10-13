<?php

namespace App\Providers;

use Exception;
use App\Enums\ThemeList;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Modules\GlobalSetting\app\Models\Setting;
use Modules\Frontend\app\Models\ContactSection;
use Modules\GlobalSetting\app\Models\SeoSetting;
use Modules\GlobalSetting\app\Models\CustomPagination;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        try {
            $setting = Cache::rememberForever('setting', function () {
                $setting_info = Setting::get();
                $setting = [];
                foreach ($setting_info as $setting_item) {
                    $setting[$setting_item->key] = $setting_item->value;
                }
                $setting = (object) $setting;

                return $setting;
            });
            $seo_setting = Cache::rememberForever('seo_setting', fn () => (object) SeoSetting::all()->groupBy('page_name')->mapWithKeys(function ($group, $pageName) {
                return [$pageName => $group->first()];
            }));
            $contactSection = Cache::rememberForever('contact_section', function () {
                return ContactSection::select(
                    'address', 'phone', 'phone_two', 'email', 'email_two', 'map',
                    'headquarters_title', 'email_title', 'phone_title',
                    'get_direction_text', 'send_message_text', 'call_anytime_text',
                    'form_title', 'form_subtitle', 'full_name_label', 'email_label',
                    'website_label', 'subject_label', 'message_label', 'submit_button_text',
                    'page_title', 'breadcrumb_title',
                    'show_website_field', 'show_second_phone', 'show_second_email'
                )->first();
            });

            // Setup mail configuration
            $mailConfig = [
                'transport'  => 'smtp',
                'host'       => $setting?->mail_host,
                'port'       => $setting?->mail_port,
                'encryption' => $setting?->mail_encryption,
                'username'   => $setting?->mail_username,
                'password'   => $setting?->mail_password,
                'timeout'    => null,
            ];

            config(['mail.mailers.smtp' => $mailConfig]);
            config(['mail.from.address' => $setting?->mail_sender_email]);
            config(['mail.from.name' => $setting?->mail_sender_name]);

            config(['app.timezone' => $setting?->timezone]);

            Cache::rememberForever('CustomPagination', function () {
                $custom_pagination = CustomPagination::all();
                $pagination = [];
                foreach ($custom_pagination as $item) {
                    $pagination[str_replace(' ', '_', strtolower($item?->section_name))] = $item?->item_qty;
                }
                $pagination = (object) $pagination;

                return $pagination;
            });
        } catch (Exception $ex) {
            $setting = (object) ['site_theme' => ThemeList::MAIN->value];
            $seo_setting = (object) [];
            $contactSection = (object) [];
            info($ex->getMessage());
        }
        /** Share settings to all views */
        View::composer('*', function ($view) use ($setting, $seo_setting, $contactSection) {
            $view->with(['setting' => $setting, 'seo_setting' => $seo_setting, 'contactSection' => $contactSection]);
        });


        $this->registerBladeDirectives();
        Paginator::useBootstrapFour();

        if (!defined('DEFAULT_HOMEPAGE')) {
            define('DEFAULT_HOMEPAGE', $setting?->site_theme ?? ThemeList::MAIN->value);
        }
        if (!defined('TINYMNCE_UPLOAD_PATH')) {
            define('TINYMNCE_UPLOAD_PATH', 'custom-images');
        }
    }

    protected function registerBladeDirectives() {
        Blade::directive('adminCan', function ($permission) {
            return "<?php if(auth()->guard('admin')->user()->can({$permission})): ?>";
        });

        Blade::directive('endadminCan', function () {
            return '<?php endif; ?>';
        });

        Blade::directive('theme', function ($themes) {
            return "<?php if(in_array(DEFAULT_HOMEPAGE, {$themes})): ?>";
        });

        Blade::directive('endtheme', function () {
            return '<?php endif; ?>';
        });
    }
}
