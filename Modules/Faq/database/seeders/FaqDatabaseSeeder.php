<?php

namespace Modules\Faq\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Faq\app\Models\Faq;
use Modules\Faq\app\Models\FaqTranslation;

class FaqDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $dummyData = [
            [
                'translations' => [
                    ['lang_code' => 'en', 'question' => 'Will you be updating the program?', 'answer' => 'We design high quality websites that make users come back for more. A good website tells a story that will make users fully immerse themselves operating'],
                    ['lang_code' => 'hi', 'question' => 'क्या आप कार्यक्रम को अद्यतन करेंगे?', 'answer' => 'हम उच्च गुणवत्ता वाली वेबसाइटें डिज़ाइन करते हैं जो उपयोगकर्ताओं को और अधिक देखने के लिए वापस लाती हैं। एक अच्छी वेबसाइट एक ऐसी कहानी बताती है जो उपयोगकर्ताओं को पूरी तरह से संचालन में डुबो देगी'],
                    ['lang_code' => 'ar', 'question' => 'هل ستقوم بتحديث البرنامج؟', 'answer' => 'نقوم بتصميم مواقع ويب عالية الجودة تجعل المستخدمين يعودون للمزيد. يروي الموقع الجيد قصة تجعل المستخدمين ينغمسون تمامًا في العمل'],
                ],
            ],
            [
                'translations' => [
                    ['lang_code' => 'en', 'question' => 'What happens to my data if I cancel?', 'answer' => 'We design high quality websites that make users come back for more. A good website tells a story that will make users fully immerse themselves operating'],
                    ['lang_code' => 'hi', 'question' => 'यदि मैं रद्द कर दूं तो मेरे डेटा का क्या होगा?', 'answer' => 'हम उच्च गुणवत्ता वाली वेबसाइटें डिज़ाइन करते हैं जो उपयोगकर्ताओं को और अधिक देखने के लिए वापस लाती हैं। एक अच्छी वेबसाइट एक ऐसी कहानी बताती है जो उपयोगकर्ताओं को पूरी तरह से संचालन में डुबो देगी'],
                    ['lang_code' => 'ar', 'question' => 'ماذا سيحدث لبياناتي إذا قمت بالإلغاء؟', 'answer' => 'نقوم بتصميم مواقع ويب عالية الجودة تجعل المستخدمين يعودون للمزيد. يروي الموقع الجيد قصة تجعل المستخدمين ينغمسون تمامًا في العمل'],
                ],
            ],
            [
                'translations' => [
                    ['lang_code' => 'en', 'question' => 'How I can optimize voice search?', 'answer' => 'We design high quality websites that make users come back for more. A good website tells a story that will make users fully immerse themselves operating'],
                    ['lang_code' => 'hi', 'question' => 'मैं ध्वनि खोज को कैसे अनुकूलित कर सकता हूँ?', 'answer' => 'हम उच्च गुणवत्ता वाली वेबसाइटें डिज़ाइन करते हैं जो उपयोगकर्ताओं को और अधिक देखने के लिए वापस लाती हैं। एक अच्छी वेबसाइट एक ऐसी कहानी बताती है जो उपयोगकर्ताओं को पूरी तरह से संचालन में डुबो देगी'],
                    ['lang_code' => 'ar', 'question' => 'كيف يمكنني تحسين البحث الصوتي؟', 'answer' => 'نقوم بتصميم مواقع ويب عالية الجودة تجعل المستخدمين يعودون للمزيد. يروي الموقع الجيد قصة تجعل المستخدمين ينغمسون تمامًا في العمل'],
                ],
            ],
            [
                'translations' => [
                    ['lang_code' => 'en', 'question' => 'If I have questions, where can I find answers?', 'answer' => 'We design high quality websites that make users come back for more. A good website tells a story that will make users fully immerse themselves operating'],
                    ['lang_code' => 'hi', 'question' => 'यदि मेरे कोई प्रश्न हों तो मैं उत्तर कहां पा सकता हूं?', 'answer' => 'हम उच्च गुणवत्ता वाली वेबसाइटें डिज़ाइन करते हैं जो उपयोगकर्ताओं को और अधिक देखने के लिए वापस लाती हैं। एक अच्छी वेबसाइट एक ऐसी कहानी बताती है जो उपयोगकर्ताओं को पूरी तरह से संचालन में डुबो देगी'],
                    ['lang_code' => 'ar', 'question' => 'إذا كان لدي أسئلة، أين يمكنني العثور على الإجابات؟', 'answer' => 'نقوم بتصميم مواقع ويب عالية الجودة تجعل المستخدمين يعودون للمزيد. يروي الموقع الجيد قصة تجعل المستخدمين ينغمسون تمامًا في العمل'],
                ],
            ],

        ];

        foreach ($dummyData as $dummy) {
            $data = new Faq();
            $data->status = true;

            if ($data->save()) {
                foreach ($dummy['translations'] as $translation) {
                    FaqTranslation::create([
                        'faq_id'    => $data->id,
                        'lang_code' => $translation['lang_code'],
                        'question'  => $translation['question'],
                        'answer'    => $translation['answer'],
                    ]);
                }
            }
        }
    }
}
