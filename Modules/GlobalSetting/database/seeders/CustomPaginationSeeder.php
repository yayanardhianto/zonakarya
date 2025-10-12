<?php

namespace Modules\GlobalSetting\database\seeders;

use Illuminate\Database\Seeder;
use Modules\GlobalSetting\app\Models\CustomPagination;

class CustomPaginationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $item1 = new CustomPagination();
        $item1->section_name = 'Blog List';
        $item1->item_qty = 4;
        $item1->save();

        $item2 = new CustomPagination();
        $item2->section_name = 'Blog Comment';
        $item2->item_qty = 4;
        $item2->save();

        $item2 = new CustomPagination();
        $item2->section_name = 'Portfolio List';
        $item2->item_qty = 4;
        $item2->save();

        $item2 = new CustomPagination();
        $item2->section_name = 'Service List';
        $item2->item_qty = 6;
        $item2->save();

        $item2 = new CustomPagination();
        $item2->section_name = 'Team List';
        $item2->item_qty = 4;
        $item2->save();

        $item3 = new CustomPagination();
        $item3->section_name = 'Language List';
        $item3->item_qty = 10;
        $item3->save();


        $item2 = new CustomPagination();
        $item2->section_name = 'Product List';
        $item2->item_qty = 6;
        $item2->save();

        $item2 = new CustomPagination();
        $item2->section_name = 'Related Product List';
        $item2->item_qty = 6;
        $item2->save();

        $item2 = new CustomPagination();
        $item2->section_name = 'Customer Reviews';
        $item2->item_qty = 4;
        $item2->save();

        $item2 = new CustomPagination();
        $item2->section_name = 'Wishlist';
        $item2->item_qty = 4;
        $item2->save();

        $item2 = new CustomPagination();
        $item2->section_name = 'Pricing Plan';
        $item2->item_qty = 4;
        $item2->save();
    }
}
