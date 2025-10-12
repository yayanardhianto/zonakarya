<?php

namespace Modules\Frontend\database\seeders;

use App\Enums\ThemeList;
use Illuminate\Database\Seeder;
use Modules\Frontend\app\Models\Home;
use Modules\Frontend\app\Models\SectionTranslation;

class SectionSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $home_pages = [
            [
                'slug'     => ThemeList::MAIN->value,
                'sections' => [
                    [
                        'name'           => 'hero_section',
                        'global_content' => [
                            'action_button_url' => '/portfolios',
                            'hero_year_image'   => 'uploads/custom-images/worldwide.svg',
                        ],
                        'translations'   => [
                            'en' => [
                                'title'              => 'Next Generation',
                                'title_two'          => 'Digital Agency',
                                'sub_title'          => 'We are digital agency that helps businesses develop immersive and engaging user experiences that drive top level growth',
                                'action_button_text' => 'View Our Works',
                                'hero_year_text'     => 'Agency of this year worldwide',
                            ],
                            'hi' => [
                                'title'              => 'आने वाली पीढ़ी',
                                'title_two'          => 'डिजिटल एजेंसी',
                                'sub_title'          => 'हम डिजिटल एजेंसी हैं जो व्यवसायों को व्यापक और आकर्षक उपयोगकर्ता अनुभव विकसित करने में मदद करती है जो शीर्ष स्तर के विकास को बढ़ावा देती है',
                                'action_button_text' => 'हमारे कार्य देखें',
                                'hero_year_text'     => 'दुनिया भर में इस साल की एजेंसी',
                            ],
                            'ar' => [
                                'title'              => 'الجيل القادم',
                                'title_two'          => 'الوكالة الرقمية',
                                'sub_title'          => 'نحن وكالة رقمية تساعد الشركات على تطوير تجارب مستخدم غامرة وجذابة تدفع النمو على أعلى مستوى',
                                'action_button_text' => 'عرض أعمالنا',
                                'hero_year_text'     => 'وكالة هذا العام في جميع أنحاء العالم',
                            ],
                        ],
                    ],
                    [
                        'name'           => 'about_section',
                        'global_content' => [
                            'button_url' => '/about',
                            'image'      => 'uploads/custom-images/about_one.jpg',
                        ],
                        'translations'   => [
                            'en' => [
                                'title'       => 'Unlock Revenue Growth for Your Business',
                                'description' => 'If you ask our clients what it’s like working with 36, they’ll
                                talk about how much we care about their success. For us, real relationships fuel
                                real success. We love building brands\\\\We are a creative agency working with brands building
                                insightful strategy, creating unique designs and crafting value',
                                'button_text' => 'About Us',
                            ],
                            'hi' => [
                                'title'       => 'अपने व्यवसाय के लिए राजस्व वृद्धि अनलॉक करें',
                                'description' => 'अगर आप हमारे क्लाइंट से पूछें कि 36 के साथ काम करना कैसा लगता है, तो वे इस बारे में बात करेंगे कि हम उनकी सफलता के बारे में कितना ध्यान रखते हैं। हमारे लिए, वास्तविक रिश्ते वास्तविक सफलता को बढ़ावा देते हैं। हमें ब्रांड बनाना पसंद है \\\\ हम एक रचनात्मक एजेंसी हैं जो ब्रांड के साथ काम करते हुए व्यावहारिक रणनीति बनाते हैं, अद्वितीय डिज़ाइन बनाते हैं और मूल्य तैयार करते हैं',
                                'button_text' => 'हमारे बारे में',
                            ],
                            'ar' => [
                                'title'       => 'إطلاق العنان لنمو الإيرادات لشركتك',
                                'description' => 'إذا سألت عملاءنا عن شعورهم بالعمل مع 36، فسيتحدثون عن مدى اهتمامنا بنجاحهم. بالنسبة لنا، العلاقات الحقيقية هي وقود النجاح الحقيقي. نحن نحب بناء العلامات التجارية \\\\
                                نحن وكالة إبداعية نعمل مع العلامات التجارية لبناء استراتيجية ثاقبة، وإنشاء تصميمات فريدة وصياغة القيمة',
                                'button_text' => 'معلومات عنا',
                            ],
                        ],
                    ],
                    [
                        'name'           => 'banner_section',
                        'global_content' => [
                            'image'     => 'uploads/custom-images/video_1.jpg',
                            'video_url' => 'https://www.youtube.com/watch?v=vvNwlRLjLkU',
                        ],
                    ],
                    [
                        'name'           => 'testimonial_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/testi_thumb1_1.jpg',
                        ],
                    ],
                    [
                        'name'           => 'counter_section',
                        'global_content' => [
                            'year_experience_count' => 26,
                            'project_count'         => 347,
                            'customer_count'        => 139,
                        ],
                        'translations'   => [
                            'en' => [
                                'year_experience_title'     => 'Years of Experience',
                                'year_experience_sub_title' => 'We are a creative agency brands building insightful strategy, creating unique designs helping',
                                'project_title'             => 'Successful Projects',
                                'project_sub_title'         => 'We are a creative agency brands building insightful strategy, creating unique designs helping',
                                'customer_title'            => 'Satisfied Customers',
                                'customer_sub_title'        => 'We are a creative agency brands building insightful strategy, creating unique designs helping',
                            ],
                            'hi' => [
                                'year_experience_title'     => 'वर्षों का अनुभव',
                                'year_experience_sub_title' => 'हम एक रचनात्मक एजेंसी हैं जो ब्रांड्स के लिए व्यावहारिक रणनीति बनाते हैं, अद्वितीय डिजाइन बनाते हैं और मदद करते हैं',
                                'project_title'             => 'सफल परियोजनाएँ',
                                'project_sub_title'         => 'हम एक रचनात्मक एजेंसी हैं जो ब्रांड्स के लिए व्यावहारिक रणनीति बनाते हैं, अद्वितीय डिजाइन बनाते हैं और मदद करते हैं',
                                'customer_title'            => 'संतुष्ट उपभोक्ता',
                                'customer_sub_title'        => 'हम एक रचनात्मक एजेंसी हैं जो ब्रांड्स के लिए व्यावहारिक रणनीति बनाते हैं, अद्वितीय डिजाइन बनाते हैं और मदद करते हैं',
                            ],
                            'ar' => [
                                'year_experience_title'     => 'سنوات من الخبرة',
                                'year_experience_sub_title' => 'نحن وكالة إبداعية تعمل على بناء استراتيجيات ثاقبة للعلامات التجارية، وإنشاء تصميمات فريدة تساعد',
                                'project_title'             => 'مشاريع ناجحة',
                                'project_sub_title'         => 'نحن وكالة إبداعية تعمل على بناء استراتيجيات ثاقبة للعلامات التجارية، وإنشاء تصميمات فريدة تساعد',
                                'customer_title'            => 'العملاء الراضون',
                                'customer_sub_title'        => 'نحن وكالة إبداعية تعمل على بناء استراتيجيات ثاقبة للعلامات التجارية، وإنشاء تصميمات فريدة تساعد',
                            ],
                        ],
                    ],
                    [
                        'name'           => 'choose_us_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/choose_us_section_1.jpg',
                        ],
                        'translations'   => [
                            'en' => [
                                'title'     => 'Passionate About Creating Quality Design',
                                'sub_title' => '<h4>We Love What We Do</h4><p>We are a creative agency working with brands building insightful strategy, creating unique designs and crafting value</p><h4>Why Work With Us</h4><p>If you ask our clients what it’s like working with 36, they’ll talk about how much we care about their success. For us, real relationships fuel real success. We love building brands</p>',
                            ],
                            'hi' => [
                                'title'     => 'गुणवत्तापूर्ण डिज़ाइन बनाने के प्रति जुनूनी',
                                'sub_title' => '<h4>हमें जो करना पसंद है</h4><p>हम एक रचनात्मक एजेंसी हैं जो ब्रांड्स के साथ मिलकर व्यावहारिक रणनीति बनाती है, अद्वितीय डिज़ाइन बनाती है और मूल्य तैयार करती है</p><h4>हमारे साथ काम क्यों करें</h4><p>अगर आप हमारे क्लाइंट से पूछें कि 36 के साथ काम करना कैसा लगता है, तो वे इस बारे में बात करेंगे कि हम उनकी सफलता के बारे में कितना ध्यान रखते हैं। हमारे लिए, वास्तविक संबंध वास्तविक सफलता को बढ़ावा देते हैं। हमें ब्रांड बनाना पसंद है</p>',
                            ],
                            'ar' => [
                                'title'     => 'شغوف بإنشاء تصميم عالي الجودة',
                                'sub_title' => '<h4>نحن نحب ما نقوم به</h4><p>نحن وكالة إبداعية نعمل مع العلامات التجارية لبناء استراتيجيات ثاقبة، وإنشاء تصميمات فريدة وصياغة القيمة</p><h4>لماذا تعمل معنا</h4><p>إذا سألت عملاءنا عن شعورهم بالعمل مع 36، فسوف يتحدثون عن مدى اهتمامنا بنجاحهم. بالنسبة لنا، العلاقات الحقيقية هي وقود النجاح الحقيقي. نحن نحب بناء العلامات التجارية</p>',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'slug'     => ThemeList::TWO->value,
                'sections' => [
                    [
                        'name'           => 'hero_section',
                        'global_content' => [
                            'action_button_url' => '/portfolios',
                            'image'             => 'uploads/custom-images/hero-2-1.jpg',
                        ],
                        'translations'   => [
                            'en' => [
                                'title'              => 'We Make',
                                'title_two'          => 'Creative Things',
                                'title_three'        => 'Everyday',
                                'sub_title'          => 'We are digital agency that helps immersive\\ and engaging user experiences that',
                                'action_button_text' => 'View Our Works',
                            ],
                            'hi' => [
                                'title'              => 'हम बनाते हैं',
                                'title_two'          => 'रचनात्मक चीजें',
                                'title_three'        => 'रोज रोज',
                                'sub_title'          => 'हम एक डिजिटल एजेंसी हैं जो इमर्सिव और आकर्षक\\ उपयोगकर्ता अनुभव में मदद करती है',
                                'action_button_text' => 'हमारे कार्य देखें',
                            ],
                            'ar' => [
                                'title'              => 'نحن نصنع',
                                'title_two'          => 'أشياء إبداعية',
                                'title_three'        => 'كل يوم',
                                'sub_title'          => 'نحن وكالة رقمية تساعد في \\توفير تجارب مستخدم غامرة وجذابة',
                                'action_button_text' => 'عرض أعمالنا',
                            ],
                        ],
                    ],
                    [
                        'name'           => 'service_feature_section',
                        'global_content' => [
                            'image'                  => 'uploads/custom-images/service_feature_section.jpg',
                            'skill_percentage_one'   => '86',
                            'skill_percentage_two'   => '69',
                            'skill_percentage_three' => '72',
                            'skill_percentage_four'  => '94',
                        ],
                        'translations'   => [
                            'en' => [
                                'title'             => 'We Offer a Wide Range of Brand Services',
                                'sub_title'         => 'We are a creative agency working with brands building insightful strategy, creating unique designs and crafting value',
                                'skill_title_one'   => 'Branding',
                                'skill_title_two'   => 'Development',
                                'skill_title_three' => 'Advertising',
                                'skill_title_four'  => 'Marketing',

                            ],
                            'hi' => [
                                'title'             => 'हम ब्रांड सेवाओं की एक विस्तृत श्रृंखला प्रदान करते हैं',
                                'sub_title'         => 'हम एक रचनात्मक एजेंसी हैं जो ब्रांडों के साथ मिलकर व्यावहारिक रणनीति बनाते हैं, अद्वितीय डिजाइन बनाते हैं और मूल्य तैयार करते हैं',
                                'skill_title_one'   => 'ब्रांडिंग',
                                'skill_title_two'   => 'विकास',
                                'skill_title_three' => 'विज्ञापन',
                                'skill_title_four'  => 'मार्केटिंग',
                            ],
                            'ar' => [
                                'title'             => 'نحن نقدم مجموعة واسعة من خدمات العلامة التجارية',
                                'sub_title'         => 'نحن وكالة إبداعية نعمل مع العلامات التجارية لبناء استراتيجية ثاقبة وإنشاء تصميمات فريدة وصياغة القيمة',
                                'skill_title_one'   => 'العلامات التجارية',
                                'skill_title_two'   => 'تطوير',
                                'skill_title_three' => 'دعاية',
                                'skill_title_four'  => 'تسويق',
                            ],
                        ],
                    ],
                    [
                        'name'           => 'banner_section',
                        'global_content' => [
                            'image'     => 'uploads/custom-images/video_2.jpg',
                            'video_url' => 'https://www.youtube.com/watch?v=vvNwlRLjLkU',
                        ],
                    ],
                    [
                        'name'           => 'testimonial_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/testi_thumb1_2.jpg',
                        ],
                    ],
                    [
                        'name'           => 'counter_section',
                        'global_content' => [
                            'year_experience_count' => 26,
                            'project_count'         => 347,
                            'customer_count'        => 139,
                        ],
                        'translations'   => [
                            'en' => [
                                'year_experience_title'     => 'Years of Experience',
                                'year_experience_sub_title' => 'We are a creative agency brands building insightful strategy, creating unique designs helping',
                                'project_title'             => 'Successful Projects',
                                'project_sub_title'         => 'We are a creative agency brands building insightful strategy, creating unique designs helping',
                                'customer_title'            => 'Satisfied Customers',
                                'customer_sub_title'        => 'We are a creative agency brands building insightful strategy, creating unique designs helping',
                            ],
                            'hi' => [
                                'year_experience_title'     => 'वर्षों का अनुभव',
                                'year_experience_sub_title' => 'हम एक रचनात्मक एजेंसी हैं जो ब्रांड्स के लिए व्यावहारिक रणनीति बनाते हैं, अद्वितीय डिजाइन बनाते हैं और मदद करते हैं',
                                'project_title'             => 'सफल परियोजनाएँ',
                                'project_sub_title'         => 'हम एक रचनात्मक एजेंसी हैं जो ब्रांड्स के लिए व्यावहारिक रणनीति बनाते हैं, अद्वितीय डिजाइन बनाते हैं और मदद करते हैं',
                                'customer_title'            => 'संतुष्ट उपभोक्ता',
                                'customer_sub_title'        => 'हम एक रचनात्मक एजेंसी हैं जो ब्रांड्स के लिए व्यावहारिक रणनीति बनाते हैं, अद्वितीय डिजाइन बनाते हैं और मदद करते हैं',
                            ],
                            'ar' => [
                                'year_experience_title'     => 'سنوات من الخبرة',
                                'year_experience_sub_title' => 'نحن وكالة إبداعية تعمل على بناء استراتيجيات ثاقبة للعلامات التجارية، وإنشاء تصميمات فريدة تساعد',
                                'project_title'             => 'مشاريع ناجحة',
                                'project_sub_title'         => 'نحن وكالة إبداعية تعمل على بناء استراتيجيات ثاقبة للعلامات التجارية، وإنشاء تصميمات فريدة تساعد',
                                'customer_title'            => 'العملاء الراضون',
                                'customer_sub_title'        => 'نحن وكالة إبداعية تعمل على بناء استراتيجيات ثاقبة للعلامات التجارية، وإنشاء تصميمات فريدة تساعد',
                            ],
                        ],
                    ],
                    [
                        'name'           => 'choose_us_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/choose_us_section_2.jpg',
                        ],
                        'translations'   => [
                            'en' => [
                                'title'     => 'Passionate About Creating Quality Design',
                                'sub_title' => '<h4>We Love What We Do</h4><p>We are a creative agency working with brands building insightful strategy, creating unique designs and crafting value</p><h4>Why Work With Us</h4><p>If you ask our clients what it’s like working with 36, they’ll talk about how much we care about their success. For us, real relationships fuel real success. We love building brands</p>',
                            ],
                            'hi' => [
                                'title'     => 'गुणवत्तापूर्ण डिज़ाइन बनाने के प्रति जुनूनी',
                                'sub_title' => '<h4>हमें जो करना पसंद है</h4><p>हम एक रचनात्मक एजेंसी हैं जो ब्रांड्स के साथ मिलकर व्यावहारिक रणनीति बनाती है, अद्वितीय डिज़ाइन बनाती है और मूल्य तैयार करती है</p><h4>हमारे साथ काम क्यों करें</h4><p>अगर आप हमारे क्लाइंट से पूछें कि 36 के साथ काम करना कैसा लगता है, तो वे इस बारे में बात करेंगे कि हम उनकी सफलता के बारे में कितना ध्यान रखते हैं। हमारे लिए, वास्तविक संबंध वास्तविक सफलता को बढ़ावा देते हैं। हमें ब्रांड बनाना पसंद है</p>',
                            ],
                            'ar' => [
                                'title'     => 'شغوف بإنشاء تصميم عالي الجودة',
                                'sub_title' => '<h4>نحن نحب ما نقوم به</h4><p>نحن وكالة إبداعية نعمل مع العلامات التجارية لبناء استراتيجيات ثاقبة، وإنشاء تصميمات فريدة وصياغة القيمة</p><h4>لماذا تعمل معنا</h4><p>إذا سألت عملاءنا عن شعورهم بالعمل مع 36، فسوف يتحدثون عن مدى اهتمامنا بنجاحهم. بالنسبة لنا، العلاقات الحقيقية هي وقود النجاح الحقيقي. نحن نحب بناء العلامات التجارية</p>',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'slug'     => ThemeList::THREE->value,
                'sections' => [
                    [
                        'name'           => 'hero_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/hero-3-1.jpg',
                        ],
                        'translations'   => [
                            'en' => [
                                'title'     => 'Design Studio',
                                'title_two' => 'with Experience',
                                'sub_title' => 'We are digital agency that helps businesses develop immersive and\\engaging user experiences that drive top level growth',
                            ],
                            'hi' => [
                                'title'     => 'हम बनाते हैं',
                                'title_two' => 'अनुभव के साथ',
                                'sub_title' => 'हम एक डिजिटल एजेंसी हैं जो व्यवसायों को इमर्सिव और आकर्षक उपयोगकर्ता अनुभव\\ विकसित करने में मदद करती है जो शीर्ष स्तर की वृद्धि को बढ़ावा देती है',
                            ],
                            'ar' => [
                                'title'     => 'استوديو التصميم',
                                'title_two' => 'مع الخبرة',
                                'sub_title' => 'نحن وكالة رقمية تساعد الشركات على تطوير تجارب مستخدم\\ غامرة وجذابة تعمل على دفع النمو إلى أعلى مستوى',
                            ],
                        ],
                    ],

                    [
                        'name'           => 'banner_section',
                        'global_content' => [
                            'image'     => 'uploads/custom-images/video_3.jpg',
                            'video_url' => 'https://www.youtube.com/watch?v=vvNwlRLjLkU',
                        ],
                    ],
                    [
                        'name'           => 'testimonial_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/testi_thumb1_3.jpg',
                        ],
                    ],
                    [
                        'name'           => 'counter_section',
                        'global_content' => [
                            'year_experience_count' => 26,
                            'project_count'         => 347,
                            'customer_count'        => 139,
                        ],
                        'translations'   => [
                            'en' => [
                                'year_experience_title'     => 'Years of Experience',
                                'year_experience_sub_title' => 'We are a creative agency brands building insightful strategy, creating unique designs helping',
                                'project_title'             => 'Successful Projects',
                                'project_sub_title'         => 'We are a creative agency brands building insightful strategy, creating unique designs helping',
                                'customer_title'            => 'Satisfied Customers',
                                'customer_sub_title'        => 'We are a creative agency brands building insightful strategy, creating unique designs helping',
                            ],
                            'hi' => [
                                'year_experience_title'     => 'वर्षों का अनुभव',
                                'year_experience_sub_title' => 'हम एक रचनात्मक एजेंसी हैं जो ब्रांड्स के लिए व्यावहारिक रणनीति बनाते हैं, अद्वितीय डिजाइन बनाते हैं और मदद करते हैं',
                                'project_title'             => 'सफल परियोजनाएँ',
                                'project_sub_title'         => 'हम एक रचनात्मक एजेंसी हैं जो ब्रांड्स के लिए व्यावहारिक रणनीति बनाते हैं, अद्वितीय डिजाइन बनाते हैं और मदद करते हैं',
                                'customer_title'            => 'संतुष्ट उपभोक्ता',
                                'customer_sub_title'        => 'हम एक रचनात्मक एजेंसी हैं जो ब्रांड्स के लिए व्यावहारिक रणनीति बनाते हैं, अद्वितीय डिजाइन बनाते हैं और मदद करते हैं',
                            ],
                            'ar' => [
                                'year_experience_title'     => 'سنوات من الخبرة',
                                'year_experience_sub_title' => 'نحن وكالة إبداعية تعمل على بناء استراتيجيات ثاقبة للعلامات التجارية، وإنشاء تصميمات فريدة تساعد',
                                'project_title'             => 'مشاريع ناجحة',
                                'project_sub_title'         => 'نحن وكالة إبداعية تعمل على بناء استراتيجيات ثاقبة للعلامات التجارية، وإنشاء تصميمات فريدة تساعد',
                                'customer_title'            => 'العملاء الراضون',
                                'customer_sub_title'        => 'نحن وكالة إبداعية تعمل على بناء استراتيجيات ثاقبة للعلامات التجارية، وإنشاء تصميمات فريدة تساعد',
                            ],
                        ],
                    ],
                    [
                        'name'           => 'choose_us_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/choose_us_section_3.jpg',
                        ],
                        'translations'   => [
                            'en' => [
                                'title'     => 'Passionate About Creating Quality Design',
                                'sub_title' => '<h4>We Love What We Do</h4><p>We are a creative agency working with brands building insightful strategy, creating unique designs and crafting value</p><h4>Why Work With Us</h4><p>If you ask our clients what it’s like working with 36, they’ll talk about how much we care about their success. For us, real relationships fuel real success. We love building brands</p>',
                            ],
                            'hi' => [
                                'title'     => 'गुणवत्तापूर्ण डिज़ाइन बनाने के प्रति जुनूनी',
                                'sub_title' => '<h4>हमें जो करना पसंद है</h4><p>हम एक रचनात्मक एजेंसी हैं जो ब्रांड्स के साथ मिलकर व्यावहारिक रणनीति बनाती है, अद्वितीय डिज़ाइन बनाती है और मूल्य तैयार करती है</p><h4>हमारे साथ काम क्यों करें</h4><p>अगर आप हमारे क्लाइंट से पूछें कि 36 के साथ काम करना कैसा लगता है, तो वे इस बारे में बात करेंगे कि हम उनकी सफलता के बारे में कितना ध्यान रखते हैं। हमारे लिए, वास्तविक संबंध वास्तविक सफलता को बढ़ावा देते हैं। हमें ब्रांड बनाना पसंद है</p>',
                            ],
                            'ar' => [
                                'title'     => 'شغوف بإنشاء تصميم عالي الجودة',
                                'sub_title' => '<h4>نحن نحب ما نقوم به</h4><p>نحن وكالة إبداعية نعمل مع العلامات التجارية لبناء استراتيجيات ثاقبة، وإنشاء تصميمات فريدة وصياغة القيمة</p><h4>لماذا تعمل معنا</h4><p>إذا سألت عملاءنا عن شعورهم بالعمل مع 36، فسوف يتحدثون عن مدى اهتمامنا بنجاحهم. بالنسبة لنا، العلاقات الحقيقية هي وقود النجاح الحقيقي. نحن نحب بناء العلامات التجارية</p>',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'slug'     => ThemeList::FOUR->value,
                'sections' => [
                    [
                        'name'           => 'hero_section',
                        'global_content' => [
                            'action_button_url' => '/portfolios',
                            'image' => 'uploads/custom-images/hero-4-1.jpg',
                            'image_two' => 'uploads/custom-images/total_customer.png',
                        ],
                        'translations'   => [
                            'en' => [
                                'title'     => 'A Digital\\Marketing\\Agency',
                                'sub_title' => 'We are digital agency that helps businesses develop immersive and\\engaging user experiences that drive top level growth',
                                'action_button_text' => 'View Our Works',
                                'total_customers' => '10k+',
                            ],
                            'hi' => [
                                'title'     => 'एक डिजिटल\\मार्केटिंग\\एजेंसी',
                                'sub_title' => 'हम एक डिजिटल एजेंसी हैं जो व्यवसायों को इमर्सिव और आकर्षक उपयोगकर्ता अनुभव\\ विकसित करने में मदद करती है जो शीर्ष स्तर की वृद्धि को बढ़ावा देती है',
                                'action_button_text' => 'हमारे कार्य देखें',
                                'total_customers' => '10k+',
                            ],
                            'ar' => [
                                'title'     => 'وكالة متخصصة في\\التسويق الرقمي\\وتطوير العلامات التجارية',
                                'sub_title' => 'نحن وكالة رقمية تساعد الشركات على تطوير تجارب مستخدم\\ غامرة وجذابة تعمل على دفع النمو إلى أعلى مستوى',
                                'action_button_text' => 'عرض أعمالنا',
                                'total_customers' => '10k+',

                            ],
                        ],
                    ],

                    [
                        'name'           => 'banner_section',
                        'global_content' => [
                            'image'     => 'uploads/custom-images/video_4.jpg',
                            'video_url' => 'https://www.youtube.com/watch?v=vvNwlRLjLkU',
                        ],
                    ],
                    [
                        'name'           => 'testimonial_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/testi_thumb1_4.jpg',
                        ],
                    ],
                    [
                        'name'           => 'counter_section',
                        'global_content' => [
                            'year_experience_count' => 26,
                            'project_count'         => 347,
                            'customer_count'        => 139,
                        ],
                        'translations'   => [
                            'en' => [
                                'year_experience_title'     => 'Years of Experience',
                                'year_experience_sub_title' => 'We are a creative agency brands building insightful strategy, creating unique designs helping',
                                'project_title'             => 'Successful Projects',
                                'project_sub_title'         => 'We are a creative agency brands building insightful strategy, creating unique designs helping',
                                'customer_title'            => 'Satisfied Customers',
                                'customer_sub_title'        => 'We are a creative agency brands building insightful strategy, creating unique designs helping',
                            ],
                            'hi' => [
                                'year_experience_title'     => 'वर्षों का अनुभव',
                                'year_experience_sub_title' => 'हम एक रचनात्मक एजेंसी हैं जो ब्रांड्स के लिए व्यावहारिक रणनीति बनाते हैं, अद्वितीय डिजाइन बनाते हैं और मदद करते हैं',
                                'project_title'             => 'सफल परियोजनाएँ',
                                'project_sub_title'         => 'हम एक रचनात्मक एजेंसी हैं जो ब्रांड्स के लिए व्यावहारिक रणनीति बनाते हैं, अद्वितीय डिजाइन बनाते हैं और मदद करते हैं',
                                'customer_title'            => 'संतुष्ट उपभोक्ता',
                                'customer_sub_title'        => 'हम एक रचनात्मक एजेंसी हैं जो ब्रांड्स के लिए व्यावहारिक रणनीति बनाते हैं, अद्वितीय डिजाइन बनाते हैं और मदद करते हैं',
                            ],
                            'ar' => [
                                'year_experience_title'     => 'سنوات من الخبرة',
                                'year_experience_sub_title' => 'نحن وكالة إبداعية تعمل على بناء استراتيجيات ثاقبة للعلامات التجارية، وإنشاء تصميمات فريدة تساعد',
                                'project_title'             => 'مشاريع ناجحة',
                                'project_sub_title'         => 'نحن وكالة إبداعية تعمل على بناء استراتيجيات ثاقبة للعلامات التجارية، وإنشاء تصميمات فريدة تساعد',
                                'customer_title'            => 'العملاء الراضون',
                                'customer_sub_title'        => 'نحن وكالة إبداعية تعمل على بناء استراتيجيات ثاقبة للعلامات التجارية، وإنشاء تصميمات فريدة تساعد',
                            ],
                        ],
                    ],
                    [
                        'name'           => 'choose_us_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/choose_us_section_4.jpg',
                        ],
                        'translations'   => [
                            'en' => [
                                'title'     => 'Empowering Your\\Businesses in the\\Digital Realm',
                                'sub_title' => '<h4>We Love What We Do</h4><p>We are a creative agency working with brands building insightful strategy, creating unique designs and crafting value</p><h4>Why Work With Us</h4><p>If you ask our clients what it’s like working with 36, they’ll talk about how much we care about their success. For us, real relationships fuel real success. We love building brands</p>',
                            ],
                            'hi' => [
                                'title'     => 'डिजिटल क्षेत्र में \\अपने व्यवसायों \\को सशक्त बनाना',
                                'sub_title' => '<h4>हमें जो करना पसंद है</h4><p>हम एक रचनात्मक एजेंसी हैं जो ब्रांड्स के साथ मिलकर व्यावहारिक रणनीति बनाती है, अद्वितीय डिज़ाइन बनाती है और मूल्य तैयार करती है</p><h4>हमारे साथ काम क्यों करें</h4><p>अगर आप हमारे क्लाइंट से पूछें कि 36 के साथ काम करना कैसा लगता है, तो वे इस बारे में बात करेंगे कि हम उनकी सफलता के बारे में कितना ध्यान रखते हैं। हमारे लिए, वास्तविक संबंध वास्तविक सफलता को बढ़ावा देते हैं। हमें ब्रांड बनाना पसंद है</p>',
                            ],
                            'ar' => [
                                'title'     => 'تمكين\\ أعمالك في \\المجال الرقمي',
                                'sub_title' => '<h4>نحن نحب ما نقوم به</h4><p>نحن وكالة إبداعية نعمل مع العلامات التجارية لبناء استراتيجيات ثاقبة، وإنشاء تصميمات فريدة وصياغة القيمة</p><h4>لماذا تعمل معنا</h4><p>إذا سألت عملاءنا عن شعورهم بالعمل مع 36، فسوف يتحدثون عن مدى اهتمامنا بنجاحهم. بالنسبة لنا، العلاقات الحقيقية هي وقود النجاح الحقيقي. نحن نحب بناء العلامات التجارية</p>',
                            ],
                        ],
                    ],
                ],
            ],
        ];
        foreach ($home_pages as $home) {
            $page = Home::create(['slug' => $home['slug']]);

            foreach ($home['sections'] as $section) {
                $page_section = $page->sections()->create(['name' => $section['name'], 'global_content' => $section['global_content']]);

                if (isset($section['translations'])) {
                    $translations = [];
                    foreach ($section['translations'] as $lang_code => $content) {
                        $translations[] = [
                            'section_id' => $page_section->id,
                            'lang_code'  => $lang_code,
                            'content'    => json_encode($content),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    SectionTranslation::insert($translations);
                }
            }
        }
    }
}
