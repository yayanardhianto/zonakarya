<?php

namespace App\Enums;

enum RouteList {
    public static function getAll(): object {
        $setting = $setting = cache()->get('setting');
        $route_list = [
            (object) [
                'name'       => __('Dashboard'),
                'route'      => route('admin.dashboard'),
                'permission' => 'dashboard.view',
            ],
            (object) [
                'name'       => __('Blog Category List'),
                'route'      => route('admin.blog-category.index'),
                'permission' => 'blog.category.view',
            ],
            (object) [
                'name'       => __('Blog List'),
                'route'      => route('admin.blogs.index'),
                'permission' => 'blog.view',
            ],
            (object) [
                'name'       => __('Blog Comments'),
                'route'      => route('admin.blog-comment.index'),
                'permission' => 'blog.comment.view',
            ],
            (object) [
                'name'       => __('Services'),
                'route'      => route('admin.service.index'),
                'permission' => 'service.view',
            ],
            (object) [
                'name'       => __('Projects'),
                'route'      => route('admin.project.index'),
                'permission' => 'project.view',
            ],
            (object) [
                'name'       => __('Our Team'),
                'route'      => route('admin.ourteam.index'),
                'permission' => 'team.management',
            ],
            (object) [
                'name'       => __('Job Vacancies'),
                'route'      => route('admin.job-vacancy.index'),
                'permission' => 'job.vacancy.view',
            ],
            (object) [
                'name'       => __('Branches'),
                'route'      => route('admin.branch.index'),
                'permission' => 'branch.view',
            ],
            (object) [
                'name'       => __('Menu Builder'),
                'route'      => route('admin.custom-menu.index'),
                'permission' => 'menu.view',
            ],
            (object) [
                'name'       => __('Customizable Page'),
                'route'      => route('admin.custom-pages.index'),
                'permission' => 'page.view',
            ],
            (object) [
                'name'       => __('FAQS'),
                'route'      => route('admin.faq.index'),
                'permission' => 'faq.view',
            ],
            (object) [
                'name'       => __('Social Links'),
                'route'      => route('admin.social-link.index'),
                'permission' => 'social.link.management',
            ],
            (object) [
                'name'       => __('Contact Page Section'),
                'route'      => route('admin.contact-section.index'),
                'permission' => 'section.management',
            ],
            (object) [
                'name'       => __('Subscriber List'),
                'route'      => route('admin.subscriber-list'),
                'permission' => 'newsletter.view',
            ],
            (object) [
                'name'       => __('Subscriber Send bulk mail'),
                'route'      => route('admin.send-mail-to-newsletter'),
                'permission' => 'newsletter.view',
            ],
            (object) [
                'name'       => __('Testimonial'),
                'route'      => route('admin.testimonial.index'),
                'permission' => 'testimonial.view',
            ],
            (object) [
                'name'       => __('Contact Messages'),
                'route'      => route('admin.contact-messages'),
                'permission' => 'contact.message.view',
            ],
            (object) [
                'name'       => __('General Settings'),
                'route'      => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab'        => 'general_tab',
            ],
            (object) [
                'name'       => __('Time & Date Setting'),
                'route'      => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab'        => 'website_tab',
            ],
            (object) [
                'name'       => __('Logo & Favicon'),
                'route'      => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab'        => 'logo_favicon_tab',
            ],
            (object) [
                'name'       => __('Cookie Consent'),
                'route'      => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab'        => 'custom_pagination_tab',
            ],
            (object) [
                'name'       => __('Default avatar'),
                'route'      => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab'        => 'default_avatar_tab',
            ],
            (object) [
                'name'       => __('Breadcrumb image'),
                'route'      => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab'        => 'breadcrump_img_tab',
            ],
            (object) [
                'name'       => __('Copyright Text'),
                'route'      => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab'        => 'copyright_text_tab',
            ],
            (object) [
                'name'       => __('Maintenance Mode'),
                'route'      => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab'        => 'mmaintenance_mode_tab',
            ],
            (object) [
                'name'       => __('Credential Settings'),
                'route'      => route('admin.crediential-setting'),
                'permission' => 'setting.view',
                'tab'        => 'google_recaptcha_tab',
            ],
            (object) [
                'name'       => __('Google reCaptcha'),
                'route'      => route('admin.crediential-setting'),
                'permission' => 'setting.view',
                'tab'        => 'google_recaptcha_tab'],
            (object) [
                'name'       => __('Google Tag Manager'),
                'route'      => route('admin.crediential-setting'),
                'permission' => 'setting.view',
                'tab'        => 'googel_tag_tab',
            ],
            (object) [
                'name'       => __('Google Analytic'),
                'route'      => route('admin.crediential-setting'),
                'permission' => 'setting.view',
                'tab'        => 'google_analytic_tab',
            ],
            (object) [
                'name'       => __('Facebook Pixel'),
                'route'      => route('admin.crediential-setting'),
                'permission' => 'setting.view',
                'tab'        => 'facebook_pixel_tab',
            ],
            (object) [
                'name'       => __('Social Login'),
                'route'      => route('admin.crediential-setting'),
                'permission' => 'setting.view',
                'tab'        => 'social_login_tab',
            ],
            (object) [
                'name'       => __('Tawk Chat'),
                'route'      => route('admin.crediential-setting'),
                'permission' => 'setting.view',
                'tab'        => 'tawk_chat_tab',
            ],
            (object) [
                'name'       => __('Email Configuration'),
                'route'      => route('admin.email-configuration'),
                'permission' => 'setting.view',
                'tab'        => 'setting_tab',
            ],
            (object) [
                'name'       => __('Email Template'),
                'route'      => route('admin.email-configuration'),
                'permission' => 'setting.view',
                'tab'        => 'email_template_tab',
            ],
            (object) [
                'name'       => __('SEO Setup'),
                'route'      => route('admin.seo-setting'),
                'permission' => 'setting.view',
            ],
            (object) [
                'name'       => __('Sitemap'),
                'route'      => route('admin.sitemap.index'),
                'permission' => 'sitemap.management',
            ],
            (object) [
                'name'       => __('Clear cache'),
                'route'      => route('admin.cache-clear'),
                'permission' => 'setting.view',
            ],
            (object) [
                'name'       => __('Database Clear'),
                'route'      => route('admin.database-clear'),
                'permission' => 'setting.view',
            ],
            (object) [
                'name'       => __('System Update'),
                'route'      => route('admin.system-update.index'),
                'permission' => 'setting.view',
            ],
            (object) [
                'name' => __('Manage Addons'),
                'route' => route('admin.addons.view'),
                'permission' => 'setting.view',
            ],
            (object) [
                'name'       => __('Manage Language'),
                'route'      => route('admin.languages.index'),
                'permission' => 'language.view',
            ],
            (object) [
                'name'       => __('Manage Admin'),
                'route'      => route('admin.admin.index'),
                'permission' => 'admin.view',
            ],
            (object) [
                'name'       => __('Role & Permissions'),
                'route'      => route('admin.role.index'),
                'permission' => 'role.view',
            ],
            (object) [
                'name'       => __('Country'),
                'route'      => route('admin.country.index', ['code' => getSessionLanguage()]),
                'permission' => 'country.view',
            ],
            (object) [
                'name'       => __('Site Themes'),
                'route'      => route('admin.site-appearance.index'),
                'permission' => 'appearance.management',
            ],
            (object) [
                'name'       => __('Section Setting'),
                'route'      => route('admin.section-setting.index'),
                'permission' => 'appearance.management',
            ],
            (object) [
                'name'       => __('Site Colors'),
                'route'      => route('admin.site-color-setting.index'),
                'permission' => 'appearance.management',
            ],

            (object) ['name' => __('Hero Section'), 'route' => route('admin.hero-section.index', ['code' => 'en']), 'permission' => 'section.management'],

            (object) [
                'name' => __('Banner Section'), 'route' => route('admin.banner-section.index'), 'permission' => 'section.management',
            ],

            (object) [
                'name' => __('Counter Section'), 'route' => route('admin.counter-section.index'), 'permission' => 'section.management',
            ],
            (object) [
                'name' => __('Choose Us Section'), 'route' => route('admin.choose-us-section.index'), 'permission' => 'section.management',
            ],
            (object) [
                'name' => __('Testimonial Section'), 'route' => route('admin.testimonial-section.index'), 'permission' => 'section.management',
            ],

            (object) [
                'name' => __('Award Section'), 'route' => route('admin.award.index', ['code' => getSessionLanguage()]), 'permission' => 'award.view',
            ],
            (object) [
                'name' => __('Marquee Section'), 'route' => route('admin.marquee.index', ['code' => getSessionLanguage()]), 'permission' => 'marquee.view',
            ],
            (object) [
                'name' => __('Contact Page Section'), 'route' => route('admin.contact-section.index'), 'permission' => 'section.management',
            ],
            (object) [
                'name' => __('Brands'), 'route' => route('admin.brand.index'), 'permission' => 'brand.management',
            ],
            (object) [
                'name' => __('About Section'), 'route' => route('admin.about-section.index', ['code' => 'en']), 'permission' => 'section.management',
            ],
        ];
        

        if (DEFAULT_HOMEPAGE == ThemeList::MAIN->value) {
            $route_list[] = (object) [
                'name' => __('About Section'), 'route' => route('admin.about-section.index', ['code' => 'en']), 'permission' => 'section.management',
            ];
        }
        if (DEFAULT_HOMEPAGE == ThemeList::TWO->value) {
            $route_list[] = (object) [
                'name' => __('Service Feature Section'), 'route' => route('admin.service-features-section.index', ['code' => 'en']) , 'permission' => 'section.management',
            ];
        }

        usort($route_list, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });

        return (object) $route_list;
    }
}