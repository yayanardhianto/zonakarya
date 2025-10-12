<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Branch;
use Modules\Service\app\Models\Service;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first few services to create branches for
        $services = Service::take(3)->get();

        if ($services->count() == 0) {
            $this->command->warn('No services found. Please run ServiceSeeder first.');
            return;
        }

        $branches = [
            [
                'service_id' => $services[0]->id,
                'name' => 'Jakarta Central Branch',
                'address' => 'Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta 10270',
                'city' => 'Jakarta Pusat',
                'province' => 'DKI Jakarta',
                'map' => 'https://maps.google.com/maps?q=Jl.+Sudirman+No.+123,+Jakarta+Pusat',
                'description' => 'Our main branch located in the heart of Jakarta. This branch provides comprehensive services with modern facilities and experienced staff.',
                'status' => true,
                'order' => 1,
            ],
            [
                'service_id' => $services[0]->id,
                'name' => 'Surabaya Branch',
                'address' => 'Jl. Tunjungan No. 45, Surabaya, Jawa Timur 60275',
                'city' => 'Surabaya',
                'province' => 'Jawa Timur',
                'map' => 'https://maps.google.com/maps?q=Jl.+Tunjungan+No.+45,+Surabaya',
                'description' => 'Strategic branch in East Java region. Equipped with latest technology and professional team to serve our clients.',
                'status' => true,
                'order' => 2,
            ],
            [
                'service_id' => $services->count() > 1 ? $services[1]->id : $services[0]->id,
                'name' => 'Bandung Branch',
                'address' => 'Jl. Asia Afrika No. 67, Bandung, Jawa Barat 40111',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'map' => 'https://maps.google.com/maps?q=Jl.+Asia+Afrika+No.+67,+Bandung',
                'description' => 'Modern branch in West Java with excellent customer service and state-of-the-art facilities.',
                'status' => true,
                'order' => 3,
            ],
            [
                'service_id' => $services->count() > 2 ? $services[2]->id : $services[0]->id,
                'name' => 'Medan Branch',
                'address' => 'Jl. Gatot Subroto No. 89, Medan, Sumatera Utara 20112',
                'city' => 'Medan',
                'province' => 'Sumatera Utara',
                'map' => 'https://maps.google.com/maps?q=Jl.+Gatot+Subroto+No.+89,+Medan',
                'description' => 'Northern Sumatra branch providing quality services to our clients in the region.',
                'status' => true,
                'order' => 4,
            ],
            [
                'service_id' => $services[0]->id,
                'name' => 'Yogyakarta Branch',
                'address' => 'Jl. Malioboro No. 12, Yogyakarta, DIY 55111',
                'city' => 'Yogyakarta',
                'province' => 'DIY',
                'map' => 'https://maps.google.com/maps?q=Jl.+Malioboro+No.+12,+Yogyakarta',
                'description' => 'Cultural city branch with traditional values and modern service approach.',
                'status' => false, // Inactive branch for testing
                'order' => 5,
            ],
        ];

        foreach ($branches as $branchData) {
            Branch::create($branchData);
        }

        $this->command->info('Branch seeder completed successfully!');
    }
}