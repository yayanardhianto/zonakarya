<?php

namespace Modules\GlobalSetting\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Modules\GlobalSetting\app\Enums\WebsiteSettingEnum;
use Modules\GlobalSetting\app\Models\CustomCode;
use Modules\GlobalSetting\app\Models\CustomPagination;
use Modules\GlobalSetting\app\Models\SeoSetting;
use Modules\GlobalSetting\app\Models\Setting;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use ZipArchive;

class GlobalSettingController extends Controller
{
    protected $cachedSetting;

    public function __construct()
    {
        $this->cachedSetting = Cache::get('setting');
    }

    public function general_setting()
    {
        checkAdminHasPermissionAndThrowException('setting.view');

        $custom_paginations = CustomPagination::all();
        $all_timezones = WebsiteSettingEnum::allTimeZones();
        $all_time_format = WebsiteSettingEnum::allTimeFormat();
        $all_date_format = WebsiteSettingEnum::allDateFormat();

        return view('globalsetting::settings.index', compact('custom_paginations', 'all_timezones', 'all_time_format', 'all_date_format'));
    }

    public function update_general_setting(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');

        $request->validate([
            'app_name'                      => 'sometimes',
            'preloader_status'              => 'sometimes',
            'timezone'                      => 'sometimes',
            'contact_message_receiver_mail' => 'sometimes|email',
            'is_queable'                    => 'sometimes|in:active,inactive',
            'comments_auto_approved'        => 'sometimes|in:active,inactive',
            'search_engine_indexing'        => 'sometimes|in:active,inactive',
            'review_auto_approved'          => 'sometimes|in:active,inactive',
        ], [
            'app_name.required'                   => __('App name is required'),
            'preloader_status.required'           => __('Preloader name is required'),
            'timezone.required'                   => __('Timezone is required'),
            'is_queable.required'                 => __('Queue is required'),
            'contact_message_receiver_mail.email' => __('The contact message receiver mail must be a valid email address.'),
            'is_queable.in'                       => __('Queue is invalid'),
            'comments_auto_approved.in'           => __('Review auto approved is invalid'),
            'search_engine_indexing.in'           => __('Search engine crawling is invalid'),
            'review_auto_approved.in'             => __('Review auto approved is invalid'),
        ]);

        foreach ($request->except('_token') as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Clear all relevant caches comprehensively
        \App\Helpers\CacheHelper::clearAllCaches();

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_logo_favicon(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'logo'       => 'nullable|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml|max:2048',
            'logo_white' => 'nullable|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml',
            'favicon'    => 'nullable|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml',
        ]);

        if ($request->file('logo')) {
            $file_name = file_upload($request->logo, 'uploads/custom-images/', $this->cachedSetting?->logo);
            Setting::where('key', 'logo')->update(['value' => $file_name]);
        }

        if ($request->file('logo_white')) {
            $file_name = file_upload($request->logo_white, 'uploads/custom-images/', $this->cachedSetting?->logo_white);
            Setting::where('key', 'logo_white')->update(['value' => $file_name]);
        }

        if ($request->file('favicon')) {
            $file_name = file_upload($request->favicon, 'uploads/custom-images/', $this->cachedSetting?->favicon);
            Setting::where('key', 'favicon')->update(['value' => $file_name]);
        }

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_cookie_consent(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'cookie_status'    => 'required',
            'border'           => 'required',
            'corners'          => 'required',
            'background_color' => 'required',
            'text_color'       => 'required',
            'border_color'     => 'required',
            'btn_bg_color'     => 'required',
            'btn_text_color'   => 'required',
            'link_text'        => 'required',
            'btn_text'         => 'required',
            'message'          => 'required',
        ], [
            'cookie_status.required'    => __('Status is required'),
            'border.required'           => __('Border is required'),
            'corners.required'          => __('Corner is required'),
            'background_color.required' => __('Background color is required'),
            'text_color.required'       => __('Text color is required'),
            'border_color.required'     => __('Border Color is required'),
            'btn_bg_color.required'     => __('Button color is required'),
            'btn_text_color.required'   => __('Button text color is required'),
            'link_text.required'        => __('Link text is required'),
            'btn_text.required'         => __('Button text is required'),
            'message.required'          => __('Message is required'),
        ]);

        Setting::where('key', 'cookie_status')->update(['value' => $request->cookie_status]);
        Setting::where('key', 'border')->update(['value' => $request->border]);
        Setting::where('key', 'corners')->update(['value' => $request->corners]);
        Setting::where('key', 'background_color')->update(['value' => $request->background_color]);
        Setting::where('key', 'text_color')->update(['value' => $request->text_color]);
        Setting::where('key', 'border_color')->update(['value' => $request->border_color]);
        Setting::where('key', 'btn_bg_color')->update(['value' => $request->btn_bg_color]);
        Setting::where('key', 'btn_text_color')->update(['value' => $request->btn_text_color]);
        Setting::where('key', 'link_text')->update(['value' => $request->link_text]);
        Setting::where('key', 'btn_text')->update(['value' => $request->btn_text]);
        Setting::where('key', 'message')->update(['value' => $request->message]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_custom_pagination(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        foreach ($request->quantities as $index => $quantity) {
            if ($request->quantities[$index] == '') {
                $notification = [
                    'message'    => __('Every field are required'),
                    'alert-type' => 'error',
                ];

                return redirect()->back()->with($notification);
            }

            $custom_pagination = CustomPagination::find($request->ids[$index]);
            $custom_pagination->item_qty = $request->quantities[$index];
            $custom_pagination->save();
        }

        // Cache update
        $custom_pagination = CustomPagination::all();
        $pagination = [];
        foreach ($custom_pagination as $item) {
            $pagination[str_replace(' ', '_', strtolower($item->section_name))] = $item->item_qty;
        }
        $pagination = (object) $pagination;
        Cache::put('CustomPagination', $pagination);

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_default_avatar(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'default_avatar' => 'nullable|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml',
        ]);

        if ($request->file('default_avatar')) {
            $file_name = file_upload($request->default_avatar, 'uploads/custom-images/', $this->cachedSetting?->default_avatar);
            Setting::where('key', 'default_avatar')->update(['value' => $file_name]);
        }

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_breadcrumb(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'breadcrumb_image'                => 'nullable|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml',
            'contact_page_breadcrumb_image'   => 'nullable|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml',
            'team_page_breadcrumb_image'      => 'nullable|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml',
            'about_page_breadcrumb_image'     => 'nullable|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml',
            'faq_page_breadcrumb_image'       => 'nullable|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml',
            'blog_page_breadcrumb_image'      => 'nullable|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml',
            'portfolio_page_breadcrumb_image' => 'nullable|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml',
            'service_page_breadcrumb_image'   => 'nullable|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml',
        ]);

        if ($request->file('breadcrumb_image')) {
            $file_name = file_upload($request->breadcrumb_image, 'uploads/custom-images/', $this->cachedSetting?->breadcrumb_image);
            Setting::where('key', 'breadcrumb_image')->update(['value' => $file_name]);
        }
        if ($request->file('contact_page_breadcrumb_image')) {
            $file_name = file_upload($request->contact_page_breadcrumb_image, 'uploads/custom-images/', $this->cachedSetting?->contact_page_breadcrumb_image);
            Setting::where('key', 'contact_page_breadcrumb_image')->update(['value' => $file_name]);
        }
        if ($request->file('team_page_breadcrumb_image')) {
            $file_name = file_upload($request->team_page_breadcrumb_image, 'uploads/custom-images/', $this->cachedSetting?->team_page_breadcrumb_image);
            Setting::where('key', 'team_page_breadcrumb_image')->update(['value' => $file_name]);
        }
        if ($request->file('about_page_breadcrumb_image')) {
            $file_name = file_upload($request->about_page_breadcrumb_image, 'uploads/custom-images/', $this->cachedSetting?->about_page_breadcrumb_image);
            Setting::where('key', 'about_page_breadcrumb_image')->update(['value' => $file_name]);
        }
        if ($request->file('faq_page_breadcrumb_image')) {
            $file_name = file_upload($request->faq_page_breadcrumb_image, 'uploads/custom-images/', $this->cachedSetting?->faq_page_breadcrumb_image);
            Setting::where('key', 'faq_page_breadcrumb_image')->update(['value' => $file_name]);
        }
        if ($request->file('blog_page_breadcrumb_image')) {
            $file_name = file_upload($request->blog_page_breadcrumb_image, 'uploads/custom-images/', $this->cachedSetting?->blog_page_breadcrumb_image);
            Setting::where('key', 'blog_page_breadcrumb_image')->update(['value' => $file_name]);
        }
        if ($request->file('portfolio_page_breadcrumb_image')) {
            $file_name = file_upload($request->portfolio_page_breadcrumb_image, 'uploads/custom-images/', $this->cachedSetting?->portfolio_page_breadcrumb_image);
            Setting::where('key', 'portfolio_page_breadcrumb_image')->update(['value' => $file_name]);
        }
        if ($request->file('service_page_breadcrumb_image')) {
            $file_name = file_upload($request->service_page_breadcrumb_image, 'uploads/custom-images/', $this->cachedSetting?->service_page_breadcrumb_image);
            Setting::where('key', 'service_page_breadcrumb_image')->update(['value' => $file_name]);
        }
        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_copyright_text(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'copyright_text' => 'required',
        ], [
            'copyright_text' => __('Copyright Text is required'),
        ]);
        Setting::where('key', 'copyright_text')->update(['value' => $request->copyright_text]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function crediential_setting()
    {
        checkAdminHasPermissionAndThrowException('setting.view');

        return view('globalsetting::credientials.index');
    }

    public function update_google_captcha(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'recaptcha_site_key'   => 'required',
            'recaptcha_secret_key' => 'required',
            'recaptcha_status'     => 'required',
        ], [
            'recaptcha_site_key.required'   => __('Site key is required'),
            'recaptcha_secret_key.required' => __('Secret key is required'),
            'recaptcha_status.required'     => __('Status is required'),
        ]);

        Setting::where('key', 'recaptcha_site_key')->update(['value' => $request->recaptcha_site_key]);
        Setting::where('key', 'recaptcha_secret_key')->update(['value' => $request->recaptcha_secret_key]);
        Setting::where('key', 'recaptcha_status')->update(['value' => $request->recaptcha_status]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }
    public function update_google_tag(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'googel_tag_status' => 'required',
            'googel_tag_id'     => 'required',
        ], [
            'googel_tag_status.required' => __('Status is required'),
            'googel_tag_id.required'     => __('Google Tag ID is required'),
        ]);

        Setting::where('key', 'googel_tag_status')->update(['value' => $request->googel_tag_status]);
        Setting::where('key', 'googel_tag_id')->update(['value' => $request->googel_tag_id]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_tawk_chat(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'tawk_status'    => 'required',
            'tawk_chat_link' => 'required',
        ], [
            'tawk_status.required'    => __('Status is required'),
            'tawk_chat_link.required' => __('Chat link is required'),
        ]);
        if (strpos($request->tawk_chat_link, 'embed.tawk.to') !== false) {
            $embedUrl = $request->tawk_chat_link;
        } elseif (strpos($request->tawk_chat_link, 'tawk.to/chat') !== false) {
            $embedUrl = str_replace('tawk.to/chat', 'embed.tawk.to', $request->tawk_chat_link);
        } else {
            $embedUrl = "https://embed.tawk.to/" . $request->tawk_chat_link;
        }

        Setting::where('key', 'tawk_status')->update(['value' => $request->tawk_status]);
        Setting::where('key', 'tawk_chat_link')->update(['value' => $embedUrl]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_google_analytic(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'google_analytic_status' => 'required',
            'google_analytic_id'     => 'required',
        ], [
            'google_analytic_status.required' => __('Status is required'),
            'google_analytic_id.required'     => __('Analytic id is required'),
        ]);

        Setting::where('key', 'google_analytic_status')->update(['value' => $request->google_analytic_status]);
        Setting::where('key', 'google_analytic_id')->update(['value' => $request->google_analytic_id]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_facebook_pixel(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'pixel_status' => 'required',
            'pixel_app_id' => 'required',
        ], [
            'pixel_status.required' => __('Status is required'),
            'pixel_app_id.required' => __('App ID is required'),
        ]);

        Setting::where('key', 'pixel_status')->update(['value' => $request->pixel_status]);
        Setting::where('key', 'pixel_app_id')->update(['value' => $request->pixel_app_id]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_social_login(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $rules = [
            'google_login_status' => 'required',
            'gmail_client_id'     => 'required',
            'gmail_secret_id'     => 'required',
            'linkedin_login_status' => 'required',
            'linkedin_client_id'     => 'required',
            'linkedin_client_secret' => 'required',
        ];
        $customMessages = [
            'google_login_status.required' => __('Google status is required'),
            'gmail_client_id.required'     => __('Google client is required'),
            'gmail_secret_id.required'     => __('Google secret is required'),
            'linkedin_login_status.required' => __('LinkedIn status is required'),
            'linkedin_client_id.required'     => __('LinkedIn client is required'),
            'linkedin_client_secret.required' => __('LinkedIn secret is required'),
        ];
        $request->validate($rules, $customMessages);

        // Update Google settings
        Setting::where('key', 'google_login_status')->update(['value' => $request->google_login_status]);
        Setting::where('key', 'gmail_client_id')->update(['value' => $request->gmail_client_id]);
        Setting::where('key', 'gmail_secret_id')->update(['value' => $request->gmail_secret_id]);

        // Update LinkedIn settings
        Setting::where('key', 'linkedin_login_status')->update(['value' => $request->linkedin_login_status]);
        Setting::where('key', 'linkedin_client_id')->update(['value' => $request->linkedin_client_id]);
        Setting::where('key', 'linkedin_client_secret')->update(['value' => $request->linkedin_client_secret]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function whatsapp_setting()
    {
        checkAdminHasPermissionAndThrowException('setting.view');
        
        return view('globalsetting::settings.whatsapp');
    }

    public function whatsapp_proxy(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.view');
        
        $action = $request->input('action');
        
        // Handle QR code image generation
        if ($action === 'generate_qr_image') {
            return $this->generateQRCodeImage($request);
        }
        
        // Determine which API endpoint to use based on action
        if ($action === 'get_qr') {
            $url = 'https://d.eva.id/api/geneva/geneva.php';
        } else {
            $url = 'https://d.eva.id/api/geneva/geneva_cek.php';
        }
        
        try {
            $client = new \GuzzleHttp\Client();
            
            // Prepare form data with all required parameters (matching original JavaScript)
            $formData = [
                'newsession' => $request->input('newsession', true),
                'devicename' => $request->input('devicename', 'byru'),
                'deviceid' => $request->input('deviceid', '158'),
                'userid' => $request->input('userid', '180'),
                'email' => $request->input('email', 'banksat5@yahoo.com')
            ];
            
            // Log the request for debugging
            \Log::info('WhatsApp API Request', [
                'url' => $url,
                'formData' => $formData,
                'action' => $action
            ]);
            
            $response = $client->post($url, [
                'json' => $formData,
                'timeout' => 30,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);
            
            $body = $response->getBody()->getContents();
            
            // Log the response for debugging
            \Log::info('WhatsApp API Response', [
                'status_code' => $response->getStatusCode(),
                'body' => $body
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $body
            ]);
            
        } catch (\Exception $e) {
            \Log::error('WhatsApp API Error', [
                'error' => $e->getMessage(),
                'url' => $url,
                'formData' => $formData ?? null
            ]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Generate QR code image using server-side approach
     */
    private function generateQRCodeImage(Request $request)
    {
        try {
            $qrData = $request->input('qr_data');
            
            if (empty($qrData)) {
                return response()->json([
                    'success' => false,
                    'error' => 'QR data is required'
                ], 400);
            }
            
            // Generate QR code using SimpleSoftwareIO\QrCode
            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(256)
                ->margin(2)
                ->generate($qrData);
            
            // Convert to base64 data URL
            $qrImage = 'data:image/svg+xml;base64,' . base64_encode($qrCode);
            
            return response()->json([
                'success' => true,
                'qr_image' => $qrImage
            ]);
            
        } catch (\Exception $e) {
            \Log::error('QR Code Generation Error', [
                'error' => $e->getMessage(),
                'qr_data' => $request->input('qr_data')
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate QR code: ' . $e->getMessage()
            ], 500);
        }
    }

    public function seo_setting()
    {
        checkAdminHasPermissionAndThrowException('setting.view');
        $pages = SeoSetting::all();

        return view('globalsetting::seo_setting', compact('pages'));
    }

    public function update_seo_setting(Request $request, $id)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $rules = [
            'seo_title'       => 'required',
            'seo_description' => 'required',
        ];
        $customMessages = [
            'seo_title.required'       => __('SEO title is required'),
            'seo_description.required' => __('SEO description is required'),
        ];
        $request->validate($rules, $customMessages);

        $page = SeoSetting::find($id);
        $page->seo_title = $request->seo_title;
        $page->seo_description = $request->seo_description;
        $page->save();

        cache()->forget('seo_setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function cache_clear()
    {
        checkAdminHasPermissionAndThrowException('setting.update');

        return view('globalsetting::cache_clear');
    }

    public function cache_clear_confirm()
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        Artisan::call('optimize:clear');

        $notification = __('Cache cleared successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function database_clear()
    {
        checkAdminHasPermissionAndThrowException('setting.view');
        return view('globalsetting::database_clear');
    }

    public function database_clear_success(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate(['password' => 'required'], ['password.required' => __('Password is required')]);
        $admin = auth('admin')->user();

        if (Hash::check($request->password, $admin->password)) {
            Artisan::call('migrate:fresh --force');
            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\FreshSeeder', '--force' => true]);
            Artisan::call('optimize:clear');

            // delete files
            $this->deleteFolderAndFiles(public_path('uploads/custom-images'));
            $this->deleteFolderAndFiles(public_path('uploads/website-images/product'));


            // Get all .json files in the lang directory
            $langDirectory = dirname(app_path()) . "/lang";
            $langFiles = File::files($langDirectory);
            foreach ($langFiles as $file) {
                if ($file->getFilename() !== 'en.json') {
                    File::delete($file->getPathname());
                }
            }

            $notification = __('Database Cleared Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];
        } else {
            $notification = __('Passwords do not match.');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
        }

        return redirect()->back()->with($notification);
    }

    public function customCode($type)
    {
        checkAdminHasPermissionAndThrowException('setting.view');
        $customCode = CustomCode::first();
        if (!$customCode) {
            $customCode = new CustomCode();
            $customCode->css = '//write your css code here without the style tag';
            $customCode->header_javascript = '//write your javascript here without the script tag';
            $customCode->body_javascript = '//write your javascript here without the script tag';
            $customCode->footer_javascript = '//write your javascript here without the script tag';
            $customCode->save();
        }
        return view('globalsetting::custom_code_' . $type, compact('customCode'));
    }

    public function customCodeUpdate(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $validatedData = $request->validate([
            'css'               => 'sometimes',
            'header_javascript' => 'sometimes',
            'body_javascript'   => 'sometimes',
            'footer_javascript' => 'sometimes',
        ]);

        $customCode = CustomCode::firstOrNew();
        $customCode->fill($validatedData);
        $customCode->save();

        Cache::forget('customCode');

        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_maintenance_mode_status()
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $status = $this->cachedSetting?->maintenance_mode == 1 ? 0 : 1;

        Setting::where('key', 'maintenance_mode')->update(['value' => $status]);

        Cache::forget('setting');

        return response()->json([
            'success' => true,
            'message' => __('Updated Successfully'),
        ]);
    }

    public function update_maintenance_mode(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'maintenance_image'       => 'nullable|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml',
            'maintenance_title'       => 'required',
            'maintenance_description' => 'required',
        ], [
            'maintenance_image'       => __('Maintenance Mode Image is required'),
            'maintenance_title'       => __('Maintenance Mode Title is required'),
            'maintenance_description' => __('Maintenance Mode Description is required'),
        ]);

        if ($request->file('maintenance_image')) {
            $file_name = file_upload($request->maintenance_image, 'uploads/custom-images/', $this->cachedSetting?->maintenance_image);
            Setting::where('key', 'maintenance_image')->update(['value' => $file_name]);
        }

        Setting::where('key', 'maintenance_title')->update(['value' => $request->maintenance_title]);
        Setting::where('key', 'maintenance_description')->update(['value' => $request->maintenance_description]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function footer_setting()
    {
        checkAdminHasPermissionAndThrowException('setting.view');
        
        return view('globalsetting::settings.footer_setting');
    }

    public function update_footer_setting(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');

        $request->validate([
            'footer_title' => 'required|string|max:255',
            'footer_description' => 'required|string|max:1000',
            'footer_button_text' => 'required|string|max:100',
        ], [
            'footer_title.required' => __('Footer title is required'),
            'footer_description.required' => __('Footer description is required'),
            'footer_button_text.required' => __('Footer button text is required'),
        ]);

        foreach ($request->except('_token') as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Clear all relevant caches comprehensively
        \App\Helpers\CacheHelper::clearAllCaches();

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function job_listing_setting()
    {
        checkAdminHasPermissionAndThrowException('setting.view');
        
        return view('globalsetting::settings.job_listing_setting');
    }

    public function update_job_listing_setting(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');

        $request->validate([
            'job_listing_title' => 'required|string|max:255',
            'job_listing_description' => 'required|string|max:1000',
            'label_location' => 'required|string|max:100',
            'label_work_type' => 'required|string|max:100',
            'label_salary_range' => 'required|string|max:100',
            'label_age_range' => 'required|string|max:100',
            'label_gender' => 'required|string|max:100',
            'label_deadline' => 'required|string|max:100',
            'label_job_description' => 'required|string|max:100',
            'label_responsibilities' => 'required|string|max:100',
            'label_requirements' => 'required|string|max:100',
            'label_benefits' => 'required|string|max:100',
            'label_company_info' => 'required|string|max:100',
            'label_apply_position' => 'required|string|max:100',
            'label_send_application' => 'required|string|max:100',
            'label_send_email' => 'required|string|max:100',
            'label_call_company' => 'required|string|max:100',
            'label_job_stats' => 'required|string|max:100',
            'label_views' => 'required|string|max:100',
            'label_posted' => 'required|string|max:100',
            'label_last_updated' => 'required|string|max:100',
            'require_screening_test' => 'sometimes|in:0,1',
        ], [
            'job_listing_title.required' => __('Job listing title is required'),
            'job_listing_description.required' => __('Job listing description is required'),
        ]);

        foreach ($request->except('_token', '_method') as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        
        // Explicitly handle require_screening_test if not in request
        // This ensures the hidden input value is always saved
        if (!$request->has('require_screening_test')) {
            Setting::updateOrCreate(
                ['key' => 'require_screening_test'],
                ['value' => '0']
            );
        }

        // Clear all relevant caches comprehensively
        \App\Helpers\CacheHelper::clearAllCaches();

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function systemUpdate()
    {
        $zipLoaded = extension_loaded('zip');
        $updateAvailablity = null;

        if (request('type') == 'check') {
            Cache::forget('update_url');
        }



        $updateFileDetails = false;
        $files = false;
        $uploadFileSize = false;

        $zipFilePath = public_path('upload/update.zip');
        if ($updateFileDetails = File::exists($zipFilePath)) {
            $uploadFileSize = File::size($zipFilePath);

            $files = $this->getFilesFromZip($zipFilePath);
        }

        $upload_max_filesize = $this->convertPHPSizeToBytes(ini_get('upload_max_filesize'));
        $post_max_size = $this->convertPHPSizeToBytes(ini_get('post_max_size'));
        $max_upload_size = min($upload_max_filesize, $post_max_size);


        return view('globalsetting::auto-update', compact( 'updateFileDetails', 'uploadFileSize', 'files', 'zipLoaded', 'max_upload_size'));
    }

    public function systemUpdateStore(Request $request)
    {
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));

        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }
        $save = $receiver->receive();
        if ($save->isFinished()) {
            $file = $save->getFile();

            $mime = $file->getClientOriginalExtension();
            if ($mime == 'zip') {
                $filePath = public_path('upload/update.zip');
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
                $file->move(public_path('upload'), 'update.zip');
            }

            return response()->json(['status' => true], 200);
        }
        $handler = $save->handler();
        return response()->json([
            "done"   => $handler->getPercentageDone(),
            'status' => true,
        ]);
    }
    private function convertPHPSizeToBytes($size)
    {
        $suffix = strtoupper(substr($size, -1));
        $value = (int) substr($size, 0, -1);
        switch ($suffix) {
            case 'G':
                $value *= 1024 * 1024 * 1024;
                break;
            case 'M':
                $value *= 1024 * 1024;
                break;
            case 'K':
                $value *= 1024;
                break;
        }
        return $value;
    }
    private function formatBytes($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $index = floor(log($size, 1024));
        $formattedSize = $size / pow(1024, $index);
        return round($formattedSize, $precision) . ' ' . $units[$index];
    }

    public function systemUpdateRedirect()
    {
        $zipFilePath = public_path('upload/update.zip');

        $zip = new ZipArchive;
        if ($zip->open($zipFilePath) !== true) {
            File::delete($zipFilePath);
            $notification = __('Corrupted Zip File');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
            $zip->close();
            return redirect()->back()->with($notification);
        }

        if (!$this->isFirstDirUpload($zipFilePath)) {
            $notification = __('Invalid Update File Structure');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
            $zip->close();
            return redirect()->back()->with($notification);
        }

        $zip->close();

        $this->deleteFolderAndFiles(base_path('update'));

        if ($zip->open($zipFilePath) === true) {
            $zip->extractTo(base_path());
            $zip->close();
        }

        return redirect(url('/update'));
    }

    public function systemUpdateDelete()
    {
        $zipFilePath = public_path('upload/update.zip');
        File::delete($zipFilePath);

        $this->deleteFolderAndFiles(base_path('update'));

        $notification = __('Deleted Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];
        return back()->with($notification);
    }

    private function getFilesFromZip($zipFilePath)
    {
        $files = [];
        $zip = new ZipArchive;
        if ($zip->open($zipFilePath) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileInfo = $zip->statIndex($i);
                $files[] = $fileInfo['name'];
            }
        }
        $zip->close();
        return $files;
    }

    private function deleteFolderAndFiles($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $path = $dir . '/' . $file;

            if (is_dir($path)) {
                $this->deleteFolderAndFiles($path);
            } else {
                unlink($path);
            }
        }

        rmdir($dir);
    }

    private function isFirstDirUpload($zipFilePath)
    {
        $zip = new ZipArchive;
        if ($zip->open($zipFilePath) === TRUE) {
            $firstDir = null;

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileInfo = $zip->statIndex($i);
                $filePathParts = explode('/', $fileInfo['name']);

                if (count($filePathParts) > 1) {
                    $firstDir = $filePathParts[0];
                    break;
                }
            }

            $zip->close();
            return $firstDir === "update";
        }

        $zip->close();
        return false;
    }
}
