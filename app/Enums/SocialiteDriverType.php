<?php

namespace App\Enums;

enum SocialiteDriverType: string {
case GOOGLE = 'google';
case LINKEDIN = 'linkedin';

    public static function getIcons(): array {
        return [
            self::GOOGLE->value => 'backend/img/google_icon.png',
            self::LINKEDIN->value => 'backend/img/linkedin_icon.png',
        ];
    }

    public static function getAll(): array {
        return [
            self::GOOGLE->value,
            self::LINKEDIN->value,
        ];
    }
}
