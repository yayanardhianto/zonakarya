<?php

namespace Modules\Frontend\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Frontend\app\Models\ContactSection;

class ContactSectionSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        ContactSection::create([
            'address'   => '27 Division St, New York, NY 10002, USA',
            'phone'     => '+1 800 123 654 987',
            'phone_two' => '+1 800 223 984 002',
            'email'     => 'frisk.agency@mail.com',
            'email_two' => 'frisk.support@mail.com',
            'map'     => 'https://maps.google.com/maps?q=London%20Eye%2C%20London%2C%20United%20Kingdom&t=m&z=10&output=embed&iwloc=near',
        ]);
    }
}
