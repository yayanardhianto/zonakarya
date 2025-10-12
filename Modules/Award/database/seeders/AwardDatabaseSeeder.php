<?php

namespace Modules\Award\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Award\app\Models\Award;
use Modules\Award\app\Models\AwardTranslation;

class AwardDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $dummyData = [
            [
                'translations' => [
                    ['lang_code' => 'en', 'year' => '2017', 'title' => 'New York Design Week', 'sub_title' => 'We bring to life the most complex projects, specialize', 'tag' => 'Main developer'],
                    ['lang_code' => 'hi', 'year' => '2017', 'title' => 'न्यूयॉर्क डिजाइन सप्ताह', 'sub_title' => 'हम सबसे जटिल परियोजनाओं को जीवंत बनाते हैं, विशेषज्ञता रखते हैं', 'tag' => 'मुख्य डेवलपर'],
                    ['lang_code' => 'ar', 'year' => '2017', 'title' => 'اسبوع التصميم في نيويورك', 'sub_title' => 'نحن نجسد المشاريع الأكثر تعقيدًا، ونتخصص', 'tag' => 'المطور الرئيسي'],
                ],
            ],
            [
                'translations' => [
                    ['lang_code' => 'en', 'year' => '2018', 'title' => 'The Blue Design Awards', 'sub_title' => 'We bring to life the most complex projects, specialize', 'tag' => 'Animator'],
                    ['lang_code' => 'hi', 'year' => '2018', 'title' => 'ब्लू डिज़ाइन पुरस्कार', 'sub_title' => 'हम सबसे जटिल परियोजनाओं को जीवंत बनाते हैं, विशेषज्ञता रखते हैं', 'tag' => 'एनिमेटर'],
                    ['lang_code' => 'ar', 'year' => '2018', 'title' => 'جوائز التصميم الأزرق', 'sub_title' => 'نحن نجسد المشاريع الأكثر تعقيدًا، ونتخصص', 'tag' => 'رسام متحرك'],
                ],
            ],
            [
                'translations' => [
                    ['lang_code' => 'en', 'year' => '2019', 'title' => 'Best Web Flow', 'sub_title' => 'We bring to life the most complex projects, specialize', 'tag' => 'Main developer'],
                    ['lang_code' => 'hi', 'year' => '2019', 'title' => 'सर्वश्रेष्ठ वेब प्रवाह', 'sub_title' => 'हम सबसे जटिल परियोजनाओं को जीवंत बनाते हैं, विशेषज्ञता रखते हैं', 'tag' => 'मुख्य डेवलपर'],
                    ['lang_code' => 'ar', 'year' => '2019', 'title' => 'أفضل تدفق ويب', 'sub_title' => 'نحن نجسد المشاريع الأكثر تعقيدًا، ونتخصص', 'tag' => 'المطور الرئيسي'],
                ],
            ],
            [
                'translations' => [
                    ['lang_code' => 'en', 'year' => '2020', 'title' => 'Berlin Interactive Award', 'sub_title' => 'We bring to life the most complex projects, specialize', 'tag' => 'Best innovations'],
                    ['lang_code' => 'hi', 'year' => '2020', 'title' => 'बर्लिन इंटरएक्टिव पुरस्कार', 'sub_title' => 'हम सबसे जटिल परियोजनाओं को जीवंत बनाते हैं, विशेषज्ञता रखते हैं', 'tag' => 'सर्वोत्तम नवाचार'],
                    ['lang_code' => 'ar', 'year' => '2020', 'title' => 'جائزة برلين التفاعلية', 'sub_title' => 'نحن نجسد المشاريع الأكثر تعقيدًا، ونتخصص', 'tag' => 'أفضل الابتكارات'],
                ],
            ],
        ];

        foreach ($dummyData as $dummy) {
            $data = new Award();
            $data->url = 'https://websolutionus.com/';

            if ($data->save()) {
                foreach ($dummy['translations'] as $translation) {
                    AwardTranslation::create([
                        'award_id' => $data->id,
                        'lang_code'      => $translation['lang_code'],
                        'year'           => $translation['year'],
                        'title'          => $translation['title'],
                        'sub_title'      => $translation['sub_title'],
                        'tag'            => $translation['tag'],
                    ]);
                }
            }
        }
    }
}
