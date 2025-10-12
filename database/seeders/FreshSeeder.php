<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Language\app\Models\Language;
use Modules\CustomMenu\database\seeders\FreshMenuSeeder;
use Modules\GlobalSetting\database\seeders\SeoInfoSeeder;
use Modules\GlobalSetting\database\seeders\EmailTemplateSeeder;
use Modules\GlobalSetting\database\seeders\CustomPaginationSeeder;
use Modules\GlobalSetting\database\seeders\GlobalSettingInfoSeeder;
use Modules\Installer\app\Models\Configuration;
use Modules\PageBuilder\database\seeders\PageBuilderDatabaseSeeder;

class FreshSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $language = new Language();
        $language->name = 'English';
        $language->code = 'en';
        $language->is_default = true;
        $language->save();



        Configuration::create(['config' => 'setup_stage', 'value' => 5]);
        Configuration::create(['config' => 'setup_complete', 'value' => 1]);

        $this->call([
            GlobalSettingInfoSeeder::class,

            CustomPaginationSeeder::class,
            EmailTemplateSeeder::class,
            SeoInfoSeeder::class,
            RolePermissionSeeder::class,
            AdminInfoSeeder::class,
            PageBuilderDatabaseSeeder::class,
            FreshMenuSeeder::class,
        ]);
    }
}
