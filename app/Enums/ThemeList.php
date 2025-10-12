<?php

namespace App\Enums;

enum ThemeList: string {
case MAIN = 'main';
case TWO = 'two';
case THREE = 'three';
case FOUR = 'four';
case CUSTOM = 'custom';

public static function themes(): object
    {
        return (object) [
            (object) [
                'name' => self::MAIN->value,
                'title' => __('Digital Agency'),
                'screenshot' => 'backend/img/digital-agency.webp',
            ],
            (object) [
                'name' => self::TWO->value,
                'title' => __('Creative Agency'),
                'screenshot' => 'backend/img/creative-agency.webp',
            ],
            (object) [
                'name' => self::THREE->value,
                'title' => __('Design Studio'),
                'screenshot' => 'backend/img/design-studio.webp',
            ],
            (object) [
                'name' => self::FOUR->value,
                'title' => __('Digital Marketing'),
                'screenshot' => 'backend/img/digital-marketing.webp',
            ],
            (object) [
                'name' => self::CUSTOM->value,
                'title' => __('Custom Template'),
                'screenshot' => 'backend/img/custom-template.webp',
            ],
        ];
    }
}

