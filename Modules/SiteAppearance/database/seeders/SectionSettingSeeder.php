<?php

namespace Modules\SiteAppearance\database\seeders;

use Illuminate\Database\Seeder;
use Modules\SiteAppearance\app\Models\SectionSetting;

class SectionSettingSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        SectionSetting::Create(
            [
                'hero_section'  => 1,
                'about_section' => 1,
                'faq_section' => 1,
                'project_section' => 1,
                'team_section' => 1,
                'testimonial_section' => 1,
                'latest_blog_section' => 1,
                'brands_section' => 1,
                'service_section' => 1,
                'service_feature_section' => 1,
                'award_section' => 1,
                'banner_section' => 1,
                'marquee_section' => 1,
                'call_to_action_section' => 1,
                'counter_section' => 1,
                'choose_us_section' => 1,
                'contact_us_section' => 1,
                'pricing_section' => 1,
            ]
        );
    }
}
