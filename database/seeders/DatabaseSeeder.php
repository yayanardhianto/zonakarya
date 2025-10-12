<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Modules\Language\app\Models\Language;
use Modules\Faq\database\seeders\FaqDatabaseSeeder;
use Modules\Blog\database\seeders\BlogDatabaseSeeder;
use Modules\Language\database\seeders\LanguageSeeder;
use Modules\Award\database\seeders\AwardDatabaseSeeder;
use Modules\Brand\database\seeders\BrandDatabaseSeeder;
use Modules\CustomMenu\database\seeders\FreshMenuSeeder;
use Modules\GlobalSetting\database\seeders\SeoInfoSeeder;
use Modules\Marquee\database\seeders\MarqueeDatabaseSeeder;
use Modules\OurTeam\database\seeders\OurTeamDatabaseSeeder;
use Modules\Project\database\seeders\ProjectDatabaseSeeder;
use Modules\Service\database\seeders\ServiceDatabaseSeeder;
use Modules\Frontend\database\seeders\FrontendDatabaseSeeder;
use Modules\Location\database\seeders\LocationDatabaseSeeder;
use Modules\GlobalSetting\database\seeders\EmailTemplateSeeder;
use Modules\Installer\database\seeders\InstallerDatabaseSeeder;
use Modules\CustomMenu\database\seeders\CustomMenuDatabaseSeeder;
use Modules\SiteAppearance\database\seeders\SectionSettingSeeder;
use Modules\SocialLink\database\seeders\SocialLinkDatabaseSeeder;
use Modules\GlobalSetting\database\seeders\CustomPaginationSeeder;
use Modules\GlobalSetting\database\seeders\GlobalSettingInfoSeeder;
use Modules\PageBuilder\database\seeders\PageBuilderDatabaseSeeder;
use Modules\Testimonial\database\seeders\TestimonialDatabaseSeeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        if (Cache::has('fresh_install') && Cache::get('fresh_install')) {
            $language = new Language();
            $language->name = 'English';
            $language->code = 'en';
            $language->is_default = true;
            $language->save();


            
            $this->call([
                InstallerDatabaseSeeder::class,
                GlobalSettingInfoSeeder::class,

                CustomPaginationSeeder::class,
                EmailTemplateSeeder::class,
                SeoInfoSeeder::class,
                RolePermissionSeeder::class,
                AdminInfoSeeder::class,
                PageBuilderDatabaseSeeder::class,
                FreshMenuSeeder::class,
            ]);
        } else {
            $this->call([
                InstallerDatabaseSeeder::class,
                LanguageSeeder::class,

                GlobalSettingInfoSeeder::class,

                CustomPaginationSeeder::class,
                EmailTemplateSeeder::class,
                SeoInfoSeeder::class,
                RolePermissionSeeder::class,
                LocationDatabaseSeeder::class,
                AdminInfoSeeder::class,
                UserInfoSeeder::class,
                FrontendDatabaseSeeder::class,
                SectionSettingSeeder::class,
                BlogDatabaseSeeder::class,
                FaqDatabaseSeeder::class,
                ServiceDatabaseSeeder::class,
                ProjectDatabaseSeeder::class,
                OurTeamDatabaseSeeder::class,
                TestimonialDatabaseSeeder::class,
                SocialLinkDatabaseSeeder::class,
                PageBuilderDatabaseSeeder::class,
                CustomMenuDatabaseSeeder::class,
                AwardDatabaseSeeder::class,
                MarqueeDatabaseSeeder::class,
                BrandDatabaseSeeder::class,


            ]);
        }
    }
}
