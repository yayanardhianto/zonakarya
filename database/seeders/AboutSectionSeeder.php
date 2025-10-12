<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Frontend\app\Models\Section;
use Modules\Frontend\app\Models\Home;

class AboutSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $theme_name = DEFAULT_HOMEPAGE;
        $home = Home::firstOrCreate(['slug' => $theme_name]);

        $sections = [
            [
                'name' => 'counter_section',
                'order' => 1,
                'is_active' => true,
                'status' => 1
            ],
            [
                'name' => 'choose_us_section',
                'order' => 2,
                'is_active' => true,
                'status' => 1
            ],
            [
                'name' => 'award_section',
                'order' => 3,
                'is_active' => true,
                'status' => 1
            ],
            [
                'name' => 'team_section',
                'order' => 4,
                'is_active' => true,
                'status' => 1
            ],
            [
                'name' => 'contact_section',
                'order' => 5,
                'is_active' => true,
                'status' => 1
            ],
            [
                'name' => 'brand_section',
                'order' => 6,
                'is_active' => true,
                'status' => 1
            ]
        ];

        foreach ($sections as $sectionData) {
            Section::updateOrCreate(
                [
                    'home_id' => $home->id,
                    'name' => $sectionData['name']
                ],
                [
                    'order' => $sectionData['order'],
                    'is_active' => $sectionData['is_active'],
                    'status' => $sectionData['status'],
                    'global_content' => []
                ]
            );
        }
    }
}