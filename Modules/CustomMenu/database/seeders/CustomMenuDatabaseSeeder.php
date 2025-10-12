<?php

namespace Modules\CustomMenu\database\seeders;

use Illuminate\Database\Seeder;
use Modules\CustomMenu\app\Models\Menu;
use Modules\CustomMenu\app\Enums\AllMenus;
use Modules\CustomMenu\app\Models\MenuItem;
use Modules\CustomMenu\app\Models\MenuTranslation;
use Modules\CustomMenu\app\Models\MenuItemTranslation;

class CustomMenuDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        function processMenuItems($menuItems, $menuId, $parentId = 0) {
            foreach ($menuItems as $item) {
                $menuItem = new MenuItem();
                $menuItem->label = $item['translations'][0]['label'];
                $menuItem->link = $item['link'];
                $menuItem->menu_id = $menuId;
                $menuItem->parent_id = $parentId;
                $menuItem->sort = $item['sort'];

                if ($menuItem->save()) {
                    foreach ($item['translations'] as $translate_item) {
                        MenuItemTranslation::create([
                            'menu_item_id' => $menuItem->id,
                            'lang_code'    => $translate_item['lang_code'],
                            'label'        => $translate_item['label'],
                        ]);
                    }

                    if (isset($item['menu_items']) && is_array($item['menu_items'])) {
                        processMenuItems($item['menu_items'], $menuId, $menuItem->id);
                    }
                }
            }
        }
        // Menu list
        $menu_list = [
            [
                'slug'         => AllMenus::MAIN_MENU,
                'translations' => [
                    ['lang_code' => 'en', 'name' => 'Main Menu'],
                    ['lang_code' => 'hi', 'name' => 'मुख्य मेन्यू'],
                    ['lang_code' => 'ar', 'name' => 'القائمة الرئيسية'],
                ],
                'menu_items'   => [
                    [
                        'link'         => '/',
                        'sort'         => 1,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Home'],
                            ['lang_code' => 'hi', 'label' => 'घर'],
                            ['lang_code' => 'ar', 'label' => 'بيت'],
                        ],
                    ],
                    [
                        'link'         => '#',
                        'sort'         => 2,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Pages'],
                            ['lang_code' => 'hi', 'label' => 'पृष्ठों'],
                            ['lang_code' => 'ar', 'label' => 'الصفحات'],
                        ],
                        'menu_items'   => array_merge(
                            [
                                [
                                    'link'         => '/about',
                                    'sort'         => 1,
                                    'translations' => [
                                        ['lang_code' => 'en', 'label' => 'About'],
                                        ['lang_code' => 'hi', 'label' => 'के बारे में'],
                                        ['lang_code' => 'ar', 'label' => 'عن'],
                                    ],
                                ],
                                [
                                    'link'         => '/services',
                                    'sort'         => 2,
                                    'translations' => [
                                        ['lang_code' => 'en', 'label' => 'Services'],
                                        ['lang_code' => 'hi', 'label' => 'सेवाएं'],
                                        ['lang_code' => 'ar', 'label' => 'خدمات'],
                                    ],
                                ],
                                [
                                    'link'         => '/team',
                                    'sort'         => 3,
                                    'translations' => [
                                        ['lang_code' => 'en', 'label' => 'Team'],
                                        ['lang_code' => 'hi', 'label' => 'टीम'],
                                        ['lang_code' => 'ar', 'label' => 'فريق'],
                                    ],
                                ],
                                [
                                    'link'         => '/pricing',
                                    'sort'         => 4,
                                    'translations' => [
                                        ['lang_code' => 'en', 'label' => 'Pricing'],
                                        ['lang_code' => 'hi', 'label' => 'मूल्य निर्धारण'],
                                        ['lang_code' => 'ar', 'label' => 'التسعير'],
                                    ],
                                ],
                                [
                                    'link'         => '/faq',
                                    'sort'         => 5,
                                    'translations' => [
                                        ['lang_code' => 'en', 'label' => 'FAQ'],
                                        ['lang_code' => 'hi', 'label' => 'सामान्य प्रश्न'],
                                        ['lang_code' => 'ar', 'label' => 'التعليمات'],
                                    ],
                                ],
                            ],
                        )
                    ],
                    [
                        'link'         => '/shop',
                        'sort'         => 3,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Shop'],
                            ['lang_code' => 'hi', 'label' => 'दुकान'],
                            ['lang_code' => 'ar', 'label' => 'محل'],
                        ],
                    ],
                    [
                        'link'         => '/portfolios',
                        'sort'         => 4,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Portfolios'],
                            ['lang_code' => 'hi', 'label' => 'विभागों'],
                            ['lang_code' => 'ar', 'label' => 'المحافظ'],
                        ],
                    ],
                    [
                        'link'         => '/blogs',
                        'sort'         => 5,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Blog'],
                            ['lang_code' => 'hi', 'label' => 'ब्लॉग'],
                            ['lang_code' => 'ar', 'label' => 'مدونة'],
                        ],
                    ],
                    [
                        'link'         => '/contact',
                        'sort'         => 6,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Contact'],
                            ['lang_code' => 'hi', 'label' => 'संपर्क'],
                            ['lang_code' => 'ar', 'label' => 'اتصال'],
                        ],
                    ],
                ],
            ],
            [
                'slug'         => AllMenus::FOOTER_MENU,
                'translations' => [
                    ['lang_code' => 'en', 'name' => 'Footer Menu'],
                    ['lang_code' => 'hi', 'name' => 'फ़ुटर मेनू'],
                    ['lang_code' => 'ar', 'name' => 'قائمة التذييل'],
                ],
                'menu_items'   => [
                    [
                        'link'         => '/about',
                        'sort'         => 1,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'About'],
                            ['lang_code' => 'hi', 'label' => 'के बारे में'],
                            ['lang_code' => 'ar', 'label' => 'عن'],
                        ],
                    ],
                    [
                        'link'         => '/portfolios',
                        'sort'         => 2,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Portfolios'],
                            ['lang_code' => 'hi', 'label' => 'विभागों'],
                            ['lang_code' => 'ar', 'label' => 'المحافظ'],
                        ],
                    ],
                    [
                        'link'         => '/services',
                        'sort'         => 3,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Services'],
                            ['lang_code' => 'hi', 'label' => 'सेवाएं'],
                            ['lang_code' => 'ar', 'label' => 'خدمات'],
                        ],
                    ],
                ],
            ],
            [
                'slug'         => AllMenus::FOOTER_SECOND_MENU,
                'translations' => [
                    ['lang_code' => 'en', 'name' => 'Footer Second Menu'],
                    ['lang_code' => 'hi', 'name' => 'फ़ुटर दूसरा मेनू'],
                    ['lang_code' => 'ar', 'name' => 'القائمة الثانية في التذييل'],
                ],
                'menu_items'   => [
                    [
                        'link'         => '/privacy-policy',
                        'sort'         => 1,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Privacy Policy'],
                            ['lang_code' => 'hi', 'label' => 'गोपनीयता नीति'],
                            ['lang_code' => 'ar', 'label' => 'سياسة الخصوصية'],
                        ],
                    ],
                    [
                        'link'         => '/terms-condition',
                        'sort'         => 2,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Terms Conditions'],
                            ['lang_code' => 'hi', 'label' => 'नियम एवं शर्तें'],
                            ['lang_code' => 'ar', 'label' => 'الشروط والأحكام'],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($menu_list as $menu) {
            $data = new Menu();
            $data->name = $menu['translations'][0]['name'];
            $data->slug = $menu['slug'];

            if ($data->save()) {
                foreach ($menu['translations'] as $translate) {
                    MenuTranslation::create([
                        'menu_id'   => $data->id,
                        'lang_code' => $translate['lang_code'],
                        'name'      => $translate['name'],
                    ]);
                }

                if (isset($menu['menu_items']) && is_array($menu['menu_items'])) {
                    processMenuItems($menu['menu_items'], $data->id, 0);
                }
            }
        }
    }
}