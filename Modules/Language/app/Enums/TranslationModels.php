<?php

namespace Modules\Language\app\Enums;

enum TranslationModels: string {
    /**
     * whenever update new case also update getAll() method
     * to return all values in array
     */
case Blog = "Modules\Blog\app\Models\BlogTranslation";
case BlogCategory = "Modules\Blog\app\Models\BlogCategoryTranslation";
case Testimonial = "Modules\Testimonial\app\Models\TestimonialTranslation";
case Faq = "Modules\Faq\app\Models\FaqTranslation";
case Menu = "Modules\CustomMenu\app\Models\MenuTranslation";
case MenuItem = "Modules\CustomMenu\app\Models\MenuItemTranslation";
case CustomizablePage = "Modules\PageBuilder\app\Models\CustomizablePageTranslation";
case Section = "Modules\Frontend\app\Models\SectionTranslation";
case Project = "Modules\Project\app\Models\ProjectTranslation";
case Service = "Modules\Service\app\Models\ServiceTranslation";
case NewsTicker = "Modules\Marquee\app\Models\NewsTickerTranslation";
case Award = "Modules\Award\app\Models\AwardTranslation";
case Country = "Modules\Location\app\Models\CountryTranslation";
case ShippingMethod = "Modules\Order\app\Models\ShippingMethodTranslation";
case Product = "Modules\Shop\app\Models\ProductTranslation";
case ProductCategory = "Modules\Shop\app\Models\ProductCategoryTranslation";
case SubscriptionPlan = "Modules\Subscription\app\Models\SubscriptionPlanTranslation";

    public static function getAll(): array {
        return [
            self::Blog->value,
            self::BlogCategory->value,
            self::Testimonial->value,
            self::Faq->value,
            self::Menu->value,
            self::MenuItem->value,
            self::CustomizablePage->value,
            self::Section->value,
            self::Project->value,
            self::Service->value,
            self::NewsTicker->value,
            self::Award->value,
            self::Country->value,
            self::ShippingMethod->value,
            self::ProductCategory->value,
            self::Product->value,
            self::SubscriptionPlan->value,
        ];
    }

    public static function igonreColumns(): array {
        return [
            'id',
            'lang_code',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }
}
