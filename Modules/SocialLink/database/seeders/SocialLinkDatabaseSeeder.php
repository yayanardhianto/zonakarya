<?php

namespace Modules\SocialLink\database\seeders;

use Illuminate\Database\Seeder;
use Modules\SocialLink\app\Models\SocialLink;

class SocialLinkDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        SocialLink::create([
            'link' => 'https://www.facebook.com',
            'icon' => 'frontend/images/facebook.png',
        ]);

        SocialLink::create([
            'link' => 'https://instagram.com',
            'icon' => 'frontend/images/instagram.png',
        ]);

        SocialLink::create([
            'link' => 'https://twitter.com',
            'icon' => 'frontend/images/twitter.png',
        ]);

        SocialLink::create([
            'link' => 'https://dribbble.com',
            'icon' => 'frontend/images/dribble.png',
        ]);
    }
}
