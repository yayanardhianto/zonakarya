<?php

namespace Modules\GlobalSetting\database\seeders;

use App\Enums\ThemeList;
use Illuminate\Database\Seeder;
use Modules\GlobalSetting\app\Models\Setting;

class GlobalSettingInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting_data = [
            'app_name' => 'Frisk',
            'version' => '1.2.1',
            'logo' => 'uploads/website-images/logo.svg',
            'logo_white' => 'uploads/website-images/logo-white.svg',
            'timezone' => 'Asia/Dhaka',
            'date_format' => 'Y-m-d',
            'time_format' => 'h:i A',
            'favicon' => 'uploads/website-images/favicon.png',
            'cookie_status' => 'active',
            'border' => 'normal',
            'corners' => 'thin',
            'background_color' => '#E3FF04',
            'text_color' => '#0A0C00',
            'border_color' => '#E3FF33',
            'btn_bg_color' => '#0A0C00',
            'btn_text_color' => '#FFFFFF',
            'link_text' => 'Read Our Privacy Policy',
            'btn_text' => 'Yes',
            'message' => 'This website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it. The latter will be set only upon approval.',
            'site_theme' => ThemeList::MAIN->value,
            'blog_layout' => 'standard',
            'primary_color' => '#E3FF04',
            'secondary_color' => '#0A0C00',
            'show_all_homepage' => '1',
            'preloader_status' => '1',
            'contact_team_member' => 'active',
            'copyright_text' => 'Copyright © ' . date('Y'),
            'recaptcha_site_key' => 'recaptcha_site_key',
            'recaptcha_secret_key' => 'recaptcha_secret_key',
            'recaptcha_status' => 'inactive',
            'tawk_status' => 'inactive',
            'tawk_chat_link' => 'tawk_chat_link',
            'googel_tag_status' => 'inactive',
            'googel_tag_id' => 'google_tag_id',
            'google_analytic_status' => 'active',
            'google_analytic_id' => 'google_analytic_id',
            'pixel_status' => 'inactive',
            'pixel_app_id' => 'pixel_app_id',
            'google_login_status' => 'inactive',
            'gmail_client_id' => 'google_client_id',
            'gmail_secret_id' => 'google_secret_id',
            'default_avatar' => 'uploads/website-images/default-avatar.png',
            'breadcrumb_image' => 'uploads/website-images/breadcrumb-image.jpg',
            'contact_page_breadcrumb_image' => 'uploads/website-images/breadcrumb-image.jpg',
            'team_page_breadcrumb_image' => 'uploads/custom-images/team_breadcrumb_image.jpg',
            'about_page_breadcrumb_image' => 'uploads/custom-images/about_breadcrumb_image.jpg',
            'faq_page_breadcrumb_image' => 'uploads/custom-images/faq_breadcrumb_image.jpg',
            'blog_page_breadcrumb_image' => 'uploads/custom-images/blog_breadcrumb_image.jpg',
            'portfolio_page_breadcrumb_image' => 'uploads/custom-images/portfolio_breadcrumb_image.jpg',
            'service_page_breadcrumb_image' => 'uploads/custom-images/service_breadcrumb_image.jpg',
            'mail_host' => 'sandbox.smtp.mailtrap.io',
            'mail_sender_email' => 'sender@gmail.com',
            'mail_username' => 'mail_username',
            'mail_password' => 'mail_password',
            'mail_port' => 2525,
            'mail_encryption' => 'ssl',
            'mail_sender_name' => 'WebSolutionUs',
            'contact_message_receiver_mail' => 'receiver@gmail.com',
            'club_point_rate' => 1,
            'club_point_status' => 'active',
            'maintenance_mode' => 0,
            'maintenance_image' => 'uploads/website-images/maintenance.jpg',
            'maintenance_title' => 'Website Under maintenance',
            'maintenance_description' => '<p>We are currently performing maintenance on our website to<br>improve your experience. Please check back later.</p>
            <p><a title="Websolutions" href="https://websolutionus.com/">Websolutions</a></p>',
            'last_update_date' => date('Y-m-d H:i:s'),
            'is_queable' => 'inactive',
            'comments_auto_approved' => 'active',
            'review_auto_approved' => 'active',
            'search_engine_indexing' => 'active',
            'is_shop' => 1,
            'is_delivery_charge' => 1,
            'tax_rate' => 15,
        ];

        foreach ($setting_data as $index => $setting_item) {
            $new_item = new Setting();
            $new_item->key = $index;
            $new_item->value = $setting_item;
            $new_item->save();
        }
    }
}
