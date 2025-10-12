<?php

namespace Modules\Service\database\seeders;

use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Modules\Service\app\Models\Service;
use Modules\Service\app\Models\ServiceTranslation;

class ServiceDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $faker = Faker::create();
        $commonData = [
            'en'  => [
                'btn_text' => 'View Details',
                'short_description' => 'We care success relationships fuel success we love building',
                'description'       => "<p>BaseCreate is pleased to announce that it has been commissioned by
                            Leighton Asia reposition its brand. We will help Leighton Asia evolve its brand strategy,
                            and will be responsible updating Leighton Asia’s brand identity, website, and other
                            collaterals.</p>
                        <p>For almost 50 years Leighton Asia, one of the region’s largest and
                            most respected construction companies, has been progressively building for a better future
                            by leveraging international expertise with local intelligence. In that time Leighton has
                            delivered some of Asia’s prestigious buildings and transformational infrastructure projects.
                        </p><h6>Remarking Services</h6><p class='sec-text mb-n1'>Leighton Asia’s brand refreshment will help position the company to meet
                        the challenges of future, as it seeks to lead the industry in technological innovation and
                        sustainable building practices to deliver long-lasting value for its clients.</p><img src='uploads/custom-images/description_service_details.jpg' alt='img'>",
            ],
            'hi'  => [
                'btn_text' => 'विवरण देखें',
                'short_description' => 'हम सफलता की परवाह करते हैं रिश्ते सफलता को बढ़ावा देते हैं हम इसे बनाना पसंद करते हैं',
                'description'       => '<p>बेसक्रिएट को यह घोषणा करते हुए खुशी हो रही है कि उसे लीटन एशिया द्वारा अपने ब्रांड को पुनः स्थापित करने का काम सौंपा गया है। हम लीटन एशिया को अपनी ब्रांड रणनीति विकसित करने में मदद करेंगे, और लीटन एशिया की ब्रांड पहचान, वेबसाइट और अन्य सहायक सामग्री को अपडेट करने की जिम्मेदारी लेंगे।</p> <p>लगभग 50 वर्षों से लीटन एशिया, क्षेत्र की सबसे बड़ी और सबसे सम्मानित निर्माण कंपनियों में से एक है, जो स्थानीय बुद्धिमत्ता के साथ अंतरराष्ट्रीय विशेषज्ञता का लाभ उठाकर बेहतर भविष्य के लिए लगातार निर्माण कर रही है। उस समय में लीटन ने एशिया की कुछ प्रतिष्ठित इमारतों और परिवर्तनकारी बुनियादी ढाँचे की परियोजनाओं को पूरा किया है। </p><h6>रिमार्किंग सेवाएँ</h6><p class="sec-text mb-n1">लीटन एशिया के ब्रांड रिफ्रेशमेंट से कंपनी को भविष्य की चुनौतियों का सामना करने में मदद मिलेगी, क्योंकि यह अपने ग्राहकों के लिए दीर्घकालिक मूल्य प्रदान करने के लिए तकनीकी नवाचार और टिकाऊ निर्माण प्रथाओं में उद्योग का नेतृत्व करना चाहता है।</p>',
            ],
            'ar'  => [
                'btn_text' => 'عرض التفاصيل',
                'short_description' => 'نحن نهتم بالعلاقات الناجحة ونحب بناءها',
                'description'       => '<p>يسر BaseCreate أن تعلن أنها حصلت على تكليف من Leighton Asia لإعادة تحديد موقع علامتها التجارية. سنساعد Leighton Asia في تطوير استراتيجية علامتها التجارية، وسنكون مسؤولين عن تحديث هوية Leighton Asia التجارية وموقعها الإلكتروني وغير ذلك من المواد الترويجية.</p>
                <p>منذ ما يقرب من 50 عامًا، تعمل Leighton Asia، إحدى أكبر شركات البناء وأكثرها احترامًا في المنطقة، على البناء بشكل تدريجي من أجل مستقبل أفضل
من خلال الاستفادة من الخبرة الدولية مع الاستخبارات المحلية. خلال ذلك الوقت، قامت Leighton
بتسليم بعض المباني المرموقة ومشاريع البنية التحتية التحويلية في آسيا.
</p><h6>خدمات إعادة صياغة</h6><p class="sec-text mb-n1">إن تجديد العلامة التجارية لشركة Leighton Asia سيساعد الشركة على مواجهة تحديات المستقبل، حيث تسعى إلى قيادة الصناعة في مجال الابتكار التكنولوجي وممارسات البناء المستدامة لتقديم قيمة طويلة الأمد لعملائها.</p>',
            ]
        ];

        //brands
        $dummyBrands = [
            [
                'icon'         => 'uploads/custom-images/service-icon-1.svg',
                'image'        => 'uploads/custom-images/service-1.png',
                'translations' => [
                    [
                        'lang_code'         => 'en',
                        'title'             => 'Branding Design',
                    ],
                    [
                        'lang_code'         => 'hi',
                        'title'             => 'ब्रांडिंग डिजाइन',
                    ],
                    [
                        'lang_code'         => 'ar',
                        'title'             => 'تصميم العلامة التجارية',
                    ],
                ],
            ],
            [
                'icon'         => 'uploads/custom-images/service-icon-2.svg',
                'image'        => 'uploads/custom-images/service-1.png',
                'translations' => [
                    [
                        'lang_code'         => 'en',
                        'title'             => 'Web Development',
                    ],
                    [
                        'lang_code'         => 'hi',
                        'title'             => 'वेब विकास',
                    ],
                    [
                        'lang_code'         => 'ar',
                        'title'             => 'تطوير الويب',
                    ],
                ],
            ],
            [
                'icon'         => 'uploads/custom-images/service-icon-3.svg',
                'image'        => 'uploads/custom-images/service-1.png',
                'translations' => [
                    [
                        'lang_code'         => 'en',
                        'title'             => 'Digital Marketing',
                    ],
                    [
                        'lang_code'         => 'hi',
                        'title'             => 'डिजिटल विपणन',
                    ],
                    [
                        'lang_code'         => 'ar',
                        'title'             => 'التسويق الرقمي',
                    ],
                ],
            ],
            [
                'icon'         => 'uploads/custom-images/service-icon-4.svg',
                'image'        => 'uploads/custom-images/service-1.png',
                'translations' => [
                    [
                        'lang_code'         => 'en',
                        'title'             => 'Illustration Modelling',
                    ],
                    [
                        'lang_code'         => 'hi',
                        'title'             => 'चित्रण मॉडलिंग',
                    ],
                    [
                        'lang_code'         => 'ar',
                        'title'             => 'النمذجة التوضيحية',
                    ],
                ],
            ],
            [
                'icon'         => 'uploads/custom-images/service-icon-5.svg',
                'image'        => 'uploads/custom-images/service-1.png',
                'translations' => [
                    [
                        'lang_code'         => 'en',
                        'title'             => 'Content Marketing',
                    ],
                    [
                        'lang_code'         => 'hi',
                        'title'             => 'कंटेंट मार्केटिंग',
                    ],
                    [
                        'lang_code'         => 'ar',
                        'title'             => 'تسويق المحتوى',
                    ],
                ],
            ],
            [
                'icon'         => 'uploads/custom-images/service-icon-6.svg',
                'image'        => 'uploads/custom-images/service-1.png',
                'translations' => [
                    [
                        'lang_code'         => 'en',
                        'title'             => 'Social Advertising',
                    ],
                    [
                        'lang_code'         => 'hi',
                        'title'             => 'सामाजिक विज्ञापन',
                    ],
                    [
                        'lang_code'         => 'ar',
                        'title'             => 'الإعلان الاجتماعي',
                    ],
                ],
            ],
        ];

        foreach ($dummyBrands as $dummy) {
            $service = new Service();

            $service->slug = Str::slug($dummy['translations'][0]['title']);
            $service->icon = $dummy['icon'];
            $service->is_popular = $faker->randomElement([true, false]);
            $service->status = true;
            $service->image = $dummy['image'];

            if ($service->save()) {
                foreach ($dummy['translations'] as $translation) {
                    ServiceTranslation::create([
                        'service_id'        => $service->id,
                        'lang_code'         => $translation['lang_code'],
                        'title'             => $translation['title'],
                        'description'       => $commonData[$translation['lang_code']]['description'],
                        'short_description' => $commonData[$translation['lang_code']]['short_description'],
                        'seo_title'         => $translation['title'],
                        'btn_text'         => $commonData[$translation['lang_code']]['btn_text'],
                    ]);
                }
            }
        }
    }
}
