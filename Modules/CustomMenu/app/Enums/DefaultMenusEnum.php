<?php

namespace Modules\CustomMenu\app\Enums;

use Illuminate\Support\Collection;

enum DefaultMenusEnum: string {
    public static function getAll(): Collection {
        $setting = $setting = cache()->get('setting');

        $all_default_menus = [
            (object) ['name' => __('Home'), 'url' => '/'],
            (object) ['name' => __('About'), 'url' => '/about'],
            (object) ['name' => __('Contact'), 'url' => '/contact'],
            (object) ['name' => __('Portfolios'), 'url' => '/portfolios'],
            (object) ['name' => __('Services'), 'url' => '/services'],
            (object) ['name' => __('Blog'), 'url' => '/blogs'],
            (object) ['name' => __('Team'), 'url' => '/team'],
            (object) ['name' => __('Pricing'), 'url' => '/pricing'],
            (object) ['name' => __('FAQ'), 'url' => '/faq'],
            (object) ['name' => __('Privacy Policy'), 'url' => '/privacy-policy'],
            (object) ['name' => __('Terms Conditions'), 'url' => '/terms-condition'],
        ];
        foreach (customPages() as $page) {
            $all_default_menus[] = (object) ['name' => $page->title, 'url' => "/page/{$page->slug}"];
        }

        // Sort the array by the 'name' property
        usort($all_default_menus, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });

        // Convert the sorted array to a collection
        return collect($all_default_menus);
    }
}
