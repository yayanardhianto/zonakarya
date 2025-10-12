<?php

namespace Modules\Brand\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Brand\app\Models\Brand;

class BrandDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $dummyBrands = [
            ['image' => 'uploads/custom-images/brand-1.svg'],
            ['image' => 'uploads/custom-images/brand-2.svg'],
            ['image' => 'uploads/custom-images/brand-3.svg'],
            ['image' => 'uploads/custom-images/brand-4.svg'],
            ['image' => 'uploads/custom-images/brand-5.svg'],
            ['image' => 'uploads/custom-images/brand-6.svg'],
            ['image' => 'uploads/custom-images/brand-7.svg'],
            ['image' => 'uploads/custom-images/brand-8.svg'],
        ];
        foreach ($dummyBrands as $dummyBrand) {
            $brand = new Brand();

            $brand->name = 'Brand';
            $brand->image = $dummyBrand['image'];
            $brand->url = 'https://websolutionus.com/';
            $brand->save();
        }
    }
}
