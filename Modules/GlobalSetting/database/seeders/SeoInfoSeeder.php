<?php

namespace Modules\GlobalSetting\database\seeders;

use Illuminate\Database\Seeder;
use Modules\GlobalSetting\app\Models\SeoSetting;

class SeoInfoSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {

        $item = new SeoSetting();
        $item->page_name = 'home_page';
        $item->seo_title = 'Home || WebSolutionUS';
        $item->seo_description = 'Home || WebSolutionUS';
        $item->save();

        $item = new SeoSetting();
        $item->page_name = 'about_page';
        $item->seo_title = 'About || WebSolutionUS';
        $item->seo_description = 'About || WebSolutionUS';
        $item->save();

        $item = new SeoSetting();
        $item->page_name = 'blog_page';
        $item->seo_title = 'Blog || WebSolutionUS';
        $item->seo_description = 'Blog || WebSolutionUS';
        $item->save();

        $item = new SeoSetting();
        $item->page_name = 'portfolio_page';
        $item->seo_title = 'Portfolio || WebSolutionUS';
        $item->seo_description = 'Portfolio || WebSolutionUS';
        $item->save();

        $item = new SeoSetting();
        $item->page_name = 'service_page';
        $item->seo_title = 'Service || WebSolutionUS';
        $item->seo_description = 'Service || WebSolutionUS';
        $item->save();

        $item = new SeoSetting();
        $item->page_name = 'contact_page';
        $item->seo_title = 'Contact || WebSolutionUS';
        $item->seo_description = 'Contact || WebSolutionUS';
        $item->save();

        $item = new SeoSetting();
        $item->page_name = 'team_page';
        $item->seo_title = 'Team || WebSolutionUS';
        $item->seo_description = 'Team || WebSolutionUS';
        $item->save();

        $item = new SeoSetting();
        $item->page_name = 'faq_page';
        $item->seo_title = 'FAQ || WebSolutionUS';
        $item->seo_description = 'FAQ || WebSolutionUS';
        $item->save();

        $item = new SeoSetting();
        $item->page_name = 'pricing_page';
        $item->seo_title = 'Pricing || WebSolutionUS';
        $item->seo_description = 'Pricing || WebSolutionUS';
        $item->save();

        $item = new SeoSetting();
        $item->page_name = 'shop_page';
        $item->seo_title = 'Shop || WebSolutionUS';
        $item->seo_description = 'Shop || WebSolutionUS';
        $item->save();

        $item = new SeoSetting();
        $item->page_name = 'cart_page';
        $item->seo_title = 'My Cart || WebSolutionUS';
        $item->seo_description = 'My Cart || WebSolutionUS';
        $item->save();

        $item = new SeoSetting();
        $item->page_name = 'checkout_page';
        $item->seo_title = 'Checkout || WebSolutionUS';
        $item->seo_description = 'Checkout || WebSolutionUS';
        $item->save();

        $item = new SeoSetting();
        $item->page_name = 'payment_page';
        $item->seo_title = 'Payment || WebSolutionUS';
        $item->seo_description = 'Payment || WebSolutionUS';
        $item->save();
    }
}
