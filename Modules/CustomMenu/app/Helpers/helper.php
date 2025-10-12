<?php

use Modules\CustomMenu\app\Enums\AllMenus;
use Modules\CustomMenu\app\Models\Menu;
use Modules\CustomMenu\app\Models\MenuItem;

if (!function_exists('mainMenu')) {
    function mainMenu() {
        return menuGetBySlug(AllMenus::MAIN_MENU);
    }
}
if (!function_exists('footerMenu')) {
    function footerMenu() {
        return menuGetBySlug(AllMenus::FOOTER_MENU);
    }
}
if (!function_exists('footerSecondMenu')) {
    function footerSecondMenu() {
        return menuGetBySlug(AllMenus::FOOTER_SECOND_MENU);
    }
}

if (!function_exists('menuGetBySlug')) {
    function menuGetBySlug($slug) {
        $menu = Menu::bySlug($slug);
        return is_null($menu) ? [] : menuGetById($menu->id);
    }
}

if (!function_exists('menuGetById')) {
    function menuGetById($menu_id) {
        $menuItem = new MenuItem;
        $menu_list = $menuItem->getAll($menu_id);

        $roots = $menu_list->where('menu_id', (integer) $menu_id)->where('parent_id', 0);

        $items = menuTree($roots, $menu_list);
        return $items;
    }
}

if (!function_exists('menuTree')) {
    function menuTree($items, $all_items) {
        $data_arr = array();
        $i = 0;
        foreach ($items as $item) {
            $data_arr[$i] = $item->toArray();
            $find = $all_items->where('parent_id', $item->id);

            $data_arr[$i]['child'] = array();

            if ($find->count()) {
                $data_arr[$i]['child'] = menuTree($find, $all_items);
            }

            $i++;
        }

        return $data_arr;
    }
}
if (!function_exists('hasActiveChild')) {
    function hasActiveChild($menu) {
        if (url()->current() == url($menu['link'])) {
            return true;
        }

        if (!empty($menu['child'])) {
            foreach ($menu['child'] as $child) {
                if (hasActiveChild($child)) {
                    return true;
                }
            }
        }

        return false;
    }
}