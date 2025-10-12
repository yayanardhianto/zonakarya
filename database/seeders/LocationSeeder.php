<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Kantor Pusat Jakarta',
                'address' => 'Jl. Sudirman No. 123, Jakarta Selatan',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '12190',
                'phone' => '+62-21-1234-5678',
                'email' => 'jakarta@atscompany.com',
                'description' => 'Kantor pusat perusahaan ATS di Jakarta',
                'is_active' => true,
            ],
            [
                'name' => 'Cabang Bandung',
                'address' => 'Jl. Asia Afrika No. 456, Bandung',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'postal_code' => '40111',
                'phone' => '+62-22-9876-5432',
                'email' => 'bandung@atscompany.com',
                'description' => 'Cabang ATS di Bandung',
                'is_active' => true,
            ],
            [
                'name' => 'Cabang Surabaya',
                'address' => 'Jl. Tunjungan No. 789, Surabaya',
                'city' => 'Surabaya',
                'province' => 'Jawa Timur',
                'postal_code' => '60275',
                'phone' => '+62-31-5555-7777',
                'email' => 'surabaya@atscompany.com',
                'description' => 'Cabang ATS di Surabaya',
                'is_active' => true,
            ],
            [
                'name' => 'Cabang Yogyakarta',
                'address' => 'Jl. Malioboro No. 321, Yogyakarta',
                'city' => 'Yogyakarta',
                'province' => 'DI Yogyakarta',
                'postal_code' => '55113',
                'phone' => '+62-274-1234-5678',
                'email' => 'yogyakarta@atscompany.com',
                'description' => 'Cabang ATS di Yogyakarta',
                'is_active' => true,
            ],
            [
                'name' => 'Cabang Bali',
                'address' => 'Jl. Legian No. 654, Kuta, Bali',
                'city' => 'Bali',
                'province' => 'Bali',
                'postal_code' => '80361',
                'phone' => '+62-361-9999-8888',
                'email' => 'bali@atscompany.com',
                'description' => 'Cabang ATS di Bali',
                'is_active' => true,
            ]
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
