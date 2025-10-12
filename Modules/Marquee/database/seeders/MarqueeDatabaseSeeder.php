<?php

namespace Modules\Marquee\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Marquee\app\Models\NewsTicker;
use Modules\Marquee\app\Models\NewsTickerTranslation;

class MarqueeDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $dummyData = [
            [
                'translations' => [
                    ['lang_code' => 'en', 'title' => 'We Give Unparalleled Flexibility'],
                    ['lang_code' => 'hi', 'title' => 'हम अद्वितीय लचीलापन देते हैं'],
                    ['lang_code' => 'ar', 'title' => 'نمنح مرونة لا مثيل لها'],
                ],
            ],
            [
                'translations' => [
                    ['lang_code' => 'en', 'title' => 'We Give Unparalleled Flexibility'],
                    ['lang_code' => 'hi', 'title' => 'हम अद्वितीय लचीलापन देते हैं'],
                    ['lang_code' => 'ar', 'title' => 'نمنح مرونة لا مثيل لها'],
                ],
            ],
            [
                'translations' => [
                    ['lang_code' => 'en', 'title' => 'We Give Unparalleled Flexibility'],
                    ['lang_code' => 'hi', 'title' => 'हम अद्वितीय लचीलापन देते हैं'],
                    ['lang_code' => 'ar', 'title' => 'نمنح مرونة لا مثيل لها'],
                ],
            ],
            [
                'translations' => [
                    ['lang_code' => 'en', 'title' => 'We Give Unparalleled Flexibility'],
                    ['lang_code' => 'hi', 'title' => 'हम अद्वितीय लचीलापन देते हैं'],
                    ['lang_code' => 'ar', 'title' => 'نمنح مرونة لا مثيل لها'],
                ],
            ],
        ];

        foreach ($dummyData as $dummy) {
            $data = new NewsTicker();

            if ($data->save()) {
                foreach ($dummy['translations'] as $translation) {
                    NewsTickerTranslation::create([
                        'news_ticker_id' => $data->id,
                        'lang_code'      => $translation['lang_code'],
                        'title'          => $translation['title'],
                    ]);
                }
            }
        }
    }
}
