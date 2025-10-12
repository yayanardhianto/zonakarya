<?php

namespace Modules\Project\database\seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Modules\Project\app\Models\Project;
use Modules\Service\app\Models\Service;
use Modules\Project\app\Models\ProjectImage;
use Modules\Project\app\Models\ProjectTranslation;

class ProjectDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $faker = Faker::create();

        $dummyData = [
            [
                'slug'           => 'product-lineup-industrial-design-for-caramba',
                'project_date'   => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                'project_author' => 'Ethan Caldwell',
                'tags'           => '[{"value":"Branding"},{"value":"Development"},{"value":"Marketing"}]',
                'image'          => 'uploads/custom-images/portfolio1_5.webp',
                'created_at'     => '2025-03-19 08:48:31',
                'translations'   => [
                    ['lang_code' => 'en', 'title' => 'Product Lineup Industrial Design for Caramba', 'project_category' => 'Marketing'],
                    ['lang_code' => 'hi', 'title' => 'ट्रेडिंग वेबसाइट डिजाइन और विकास', 'project_category' => 'विपणन'],
                    ['lang_code' => 'ar', 'title' => 'تصميم وتطوير مواقع التداول', 'project_category' => 'تسويق'],
                ],
            ],
            [
                'slug'           => 'trading-website-design-development',
                'project_date'   => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                'project_author' => 'Olivia Sinclair',
                'tags'           => '[{"value":"Development"},{"value":"Business"}]',
                'image'          => 'uploads/custom-images/portfolio1_6.webp',
                'created_at'     => '2025-03-19 08:49:31',
                'translations'   => [
                    ['lang_code' => 'en', 'title' => 'Trading Website Design & Development', 'project_category' => 'Business'],
                    ['lang_code' => 'hi', 'title' => 'ट्रेडिंग वेबसाइट डिजाइन और विकास', 'project_category' => 'व्यापार'],
                    ['lang_code' => 'ar', 'title' => 'تصميم وتطوير مواقع التداول', 'project_category' => 'عمل'],
                ],
            ],
            [
                'slug'           => 'shopify-redesign-for-a-nova-scotia-winery',
                'project_date'   => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                'project_author' => 'Willim John',
                'tags'           => '[{"value":"Shopify"}]',
                'image'          => 'uploads/custom-images/portfolio1_4.webp',
                'created_at'     => '2025-03-19 08:50:31',
                'translations'   => [
                    ['lang_code' => 'en', 'title' => 'Shopify Redesign for a Nova Scotia Winery', 'project_category' => 'Shopify'],
                    ['lang_code' => 'hi', 'title' => 'नोवा स्कोटिया वाइनरी के लिए शॉपिफ़ाई रीडिज़ाइन', 'project_category' => 'Shopify'],
                    ['lang_code' => 'ar', 'title' => 'إعادة تصميم Shopify لمصنع نبيذ نوفا سكوشا', 'project_category' => 'شوبيفاي'],
                ],

            ],
            [
                'slug'           => 'anti-money-laundering-compliance-scanner',
                'project_date'   => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                'project_author' => 'Tom Cruise',
                'tags'           => '[{"value":"Python"},{"value":"React"}]',
                'image'          => 'uploads/custom-images/portfolio1_3.webp',
                'created_at'     => '2025-03-19 08:51:31',
                'translations'   => [
                    ['lang_code' => 'en', 'title' => 'Anti Money Laundering Compliance Scanner', 'project_category' => 'Branding'],
                    ['lang_code' => 'hi', 'title' => 'एंटी मनी लॉन्ड्रिंग अनुपालन स्कैनर', 'project_category' => 'ब्रांडिंग'],
                    ['lang_code' => 'ar', 'title' => 'ماسح الامتثال لمكافحة غسيل الأموال', 'project_category' => 'العلامة التجارية'],
                ],

            ],
            [
                'slug'           => 'decentralized-lending-platform-for-students',
                'project_date'   => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                'project_author' => 'Towfik Hasan',
                'tags'           => '[{"value":"Laravel"},{"value":"React"}]',
                'image'          => 'uploads/custom-images/portfolio1_2.webp',
                'created_at'     => '2025-03-19 08:52:31',
                'translations'   => [
                    ['lang_code' => 'en', 'title' => 'Decentralized Lending Platform for Students', 'project_category' => 'Development'],
                    ['lang_code' => 'hi', 'title' => 'छात्रों के लिए विकेन्द्रीकृत ऋण मंच', 'project_category' => 'विकास'],
                    ['lang_code' => 'ar', 'title' => 'منصة إقراض لامركزية للطلاب', 'project_category' => 'تطوير'],
                ],
            ],
            [
                'slug'           => 'money-laundering-compliance-scanner',
                'project_date'   => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                'project_author' => 'Eunice Mills',
                'tags'           => '[{"value":"WordPress"},{"value":"Figma"}]',
                'image'          => 'uploads/custom-images/portfolio1_1.webp',
                'created_at'     => '2025-03-19 08:53:31',
                'translations'   => [
                    ['lang_code' => 'en', 'title' => 'Money Laundering Compliance Scanner', 'project_category' => 'Business'],
                    ['lang_code' => 'hi', 'title' => 'मनी लॉन्ड्रिंग अनुपालन स्कैनर', 'project_category' => 'व्यापार'],
                    ['lang_code' => 'ar', 'title' => 'ماسح الامتثال لغسيل الأموال', 'project_category' => 'عمل'],
                ],
            ],
        ];

        $description = [
            'en' => '<p>BaseCreate is pleased to announce that it has been commissioned by Leighton Asia reposition its brand. We will help Leighton Asia evolve its brand strategy, and will be responsible updating Leighton Asia&rsquo;s brand identity, website, and other collaterals.</p>
                        <h6>Decentralized Platform</h6>
                        <ul>
                        <li>BaseCreate is pleased to announce that it has been commissioned.</li>
                        <li>BaseCreate is pleased to announce that it has been.</li>
                        <li>BaseCreate is pleased to announce that.</li>
                        </ul>
                        <p>For almost 50 years Leighton Asia, one of the region&rsquo;s largest and most respected construction companies, has been progressively building for a better future by leveraging international expertise with local intelligence. In that time Leighton has delivered some of Asia&rsquo;s prestigious buildings and transformational infrastructure projects.</p>

                        <h6>Challenge &amp; Solution</h6>
                        <p class="sec-text mb-n1">Future, as it seeks to lead the industry in technological innovation and sustainable building practices to deliver long-lasting value for its clients.</p>
                        <h6 class="mt-35">Final Result</h6>
                        <p class="sec-text mb-n1">For almost 50 years Leighton Asia, one of the region&rsquo;s largest and most respected construction companies, has been progressively building for a better future by leveraging international expertise with local intelligence. In that time Leighton has delivered some of Asia&rsquo;s prestigious buildings and transformational infrastructure projects.</p>',

            'hi' => '<p>बेसक्रिएट को यह घोषणा करते हुए खुशी हो रही है कि इसे लीटन एशिया द्वारा अपने ब्रांड को पुनः स्थापित करने के लिए कमीशन दिया गया है। हम लीटन एशिया को अपनी ब्रांड रणनीति विकसित करने में मदद करेंगे, और लीटन एशिया की ब्रांड पहचान, वेबसाइट और अन्य संपार्श्विक को अपडेट करने के लिए जिम्मेदार होंगे।</p>
                        <h6>विकेंद्रीकृत प्लेटफ़ॉर्म</h6>
                        <ul>
                        <li>बेसक्रिएट को यह घोषणा करते हुए खुशी हो रही है कि इसे कमीशन दिया गया है।</li>
                        <li>बेसक्रिएट को यह घोषणा करते हुए खुशी हो रही है।</li>
                        </ul>
                        <p>लगभग 50 वर्षों से लीटन एशिया, क्षेत्र की सबसे बड़ी और सबसे सम्मानित निर्माण कंपनियों में से एक है, जो स्थानीय बुद्धिमत्ता के साथ अंतर्राष्ट्रीय विशेषज्ञता का लाभ उठाकर बेहतर भविष्य के लिए उत्तरोत्तर निर्माण कर रही है। उस समय में लीटन ने एशिया की कुछ प्रतिष्ठित इमारतों और परिवर्तनकारी बुनियादी ढांचे की परियोजनाओं को पूरा किया है।</p>

                        <h6>चुनौती और समाधान</h6>
                        <p class="sec-text mb-n1">भविष्य, क्योंकि यह अपने ग्राहकों के लिए दीर्घकालिक मूल्य प्रदान करने के लिए तकनीकी नवाचार और टिकाऊ निर्माण प्रथाओं में उद्योग का नेतृत्व करना चाहता है।</p>
                        <h6 class="mt-35">अंतिम परिणाम</h6>
                        <p class="sec-text mb-n1">लगभग 50 वर्षों से लीटन एशिया, क्षेत्र की सबसे बड़ी और सबसे प्रतिष्ठित निर्माण कंपनियों में से एक, स्थानीय बुद्धिमत्ता के साथ अंतरराष्ट्रीय विशेषज्ञता का लाभ उठाकर बेहतर भविष्य के लिए उत्तरोत्तर निर्माण कर रही है। उस समय में लीटन ने एशिया की कुछ प्रतिष्ठित इमारतों और परिवर्तनकारी बुनियादी ढांचे की परियोजनाओं को पूरा किया है।</p>',

            'ar' => '<p>يسر BaseCreate أن تعلن أنها حصلت على تكليف من Leighton Asia لإعادة تحديد موقع علامتها التجارية. سنساعد Leighton Asia في تطوير استراتيجية علامتها التجارية، وسنكون مسؤولين عن تحديث هوية علامة Leighton Asia التجارية وموقعها الإلكتروني والضمانات الأخرى.</p>
                        <h6>منصة لامركزية</h6>
                        <ul>
                        <li>يسر BaseCreate أن تعلن أنها حصلت على تكليف.</li>
                        <li>يسر BaseCreate أن تعلن أنها حصلت على تكليف.</li>
                        <li>يسر BaseCreate أن تعلن أنه تم تكليف.</li>
                        </ul>
                        <p>منذ ما يقرب من 50 عامًا، تعمل Leighton Asia، إحدى أكبر شركات البناء وأكثرها احترامًا في المنطقة، على البناء بشكل تدريجي من أجل مستقبل أفضل من خلال الاستفادة من الخبرة الدولية مع الاستخبارات المحلية. خلال تلك الفترة، قامت شركة Leighton بتسليم بعض المباني المرموقة ومشاريع البنية التحتية التحويلية في آسيا.</p>

                        <h6>التحدي والحل</h6>
                        <p class="sec-text mb-n1">المستقبل، حيث تسعى إلى قيادة الصناعة في الابتكار التكنولوجي وممارسات البناء المستدامة لتقديم قيمة طويلة الأمد لعملائها.</p>
                        <h6 class="mt-35">النتيجة النهائية</h6>
                        <p class="sec-text mb-n1">لمدة 50 عامًا تقريبًا، كانت شركة Leighton Asia، إحدى أكبر شركات البناء وأكثرها احترامًا في المنطقة، تعمل بشكل تدريجي على البناء من أجل مستقبل أفضل من خلال الاستفادة من الخبرة الدولية مع الذكاء المحلي. خلال تلك الفترة، قامت شركة Leighton بتسليم بعض المباني المرموقة ومشاريع البنية التحتية التحويلية في آسيا.</p>',
        ];

        $gallery = [ 'uploads/custom-images/portfolio_inner_1.webp','uploads/custom-images/portfolio_inner_2.webp'];

        foreach ($dummyData as $dummy) {
            $data = new Project();
            $data->slug = $dummy['slug'];
            $data->service_id = Service::inRandomOrder()->first()->id ?? 1;
            $data->project_date = $dummy['project_date'];
            $data->project_author = $dummy['project_author'];
            $data->tags = $dummy['tags'];
            $data->image = $dummy['image'];
            $data->created_at = $dummy['created_at'];

            if ($data->save()) {
                foreach ($dummy['translations'] as $translation) {
                    $dataTranslation = new ProjectTranslation();
                    $dataTranslation->lang_code = $translation['lang_code'];
                    $dataTranslation->project_id = $data->id;
                    $dataTranslation->title = $translation['title'];
                    $dataTranslation->description = $description[$translation['lang_code']];
                    $dataTranslation->project_category = $translation['project_category'];
                    $dataTranslation->seo_title = $translation['title'];
                    $dataTranslation->save();
                }

                foreach ($gallery as $image) {
                    ProjectImage::create([
                        'project_id' => $data->id,
                        'small_image'   => $image,
                        'large_image'   => $image,
                    ]);
                }
            }
        }
    }
}
