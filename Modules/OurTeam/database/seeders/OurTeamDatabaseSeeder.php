<?php

namespace Modules\OurTeam\database\seeders;

use Illuminate\Database\Seeder;
use Modules\OurTeam\app\Models\OurTeam;
use Faker\Factory as Faker;

class OurTeamDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $dummyData = [
            [
                'name' => 'Daniyel Karlos',
                'slug' => 'daniyel-karlos',
                'designation' => 'Web Developer',
                'image' => 'uploads/custom-images/team-1-1.webp',
            ],
            [
                'name' => 'Ashikur Rahman',
                'slug' => 'ashikur-rahman',
                'designation' => 'Graphic Designer',
                'image' => 'uploads/custom-images/team-1-2.webp',
            ],
            [
                'name' => 'Albert Flores',
                'slug' => 'albert-flores',
                'designation' => 'Web Designer',
                'image' => 'uploads/custom-images/team-1-3.webp',
            ],
            [
                'name' => 'Arnoldo Flint',
                'slug' => 'arnoldo-flint',
                'designation' => 'IT expert',
                'image' => 'uploads/custom-images/team-1-4.webp',
            ],
        ];

        foreach ($dummyData as $data) {
            OurTeam::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'designation' => $data['designation'],
                'sort_description' => 'BaseCreate is pleased to announce that it has been commissioned by Leighton Asia reposition its brand. We will help Leighton Asia evolve its brand strategy, and will be responsible updating Leighton Asia’s brand identity, website, and other collaterals. \\\\For almost 50 years Leighton Asia, one of the region’s largest most respected construction companies been progressively',
                'facebook' => 'https://www.facebook.com/',
                'twitter' => 'https://twitter.com/',
                'instagram' => 'https://www.instagram.com/',
                'dribbble' => 'https://dribbble.com/',
                'image' => $data['image'],
                'status' => 'active',
            ]);
        }
    }
}
