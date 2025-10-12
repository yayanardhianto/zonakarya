<?php

namespace Modules\Blog\database\seeders;

use App\Models\Admin;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Blog\app\Models\Blog;
use Modules\Blog\app\Models\BlogCategory;
use Modules\Blog\app\Models\BlogCategoryTranslation;
use Modules\Blog\app\Models\BlogComment;
use Modules\Blog\app\Models\BlogTranslation;

class BlogDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $faker = Faker::create();

        $dummyCategories = [
            [
                'translations' => [
                    ['lang_code' => 'en', 'title' => 'Branding'],
                    ['lang_code' => 'hi', 'title' => 'ब्रांडिंग'],
                    ['lang_code' => 'ar', 'title' => 'العلامة التجارية'],
                ],
            ],
            [
                'translations' => [
                    ['lang_code' => 'en', 'title' => 'Design'],
                    ['lang_code' => 'hi', 'title' => 'डिज़ाइन'],
                    ['lang_code' => 'ar', 'title' => 'تصميم'],
                ],
            ],
            [
                'translations' => [
                    ['lang_code' => 'en', 'title' => 'Business'],
                    ['lang_code' => 'hi', 'title' => 'व्यापार'],
                    ['lang_code' => 'ar', 'title' => 'عمل'],
                ],
            ],
            [
                'translations' => [
                    ['lang_code' => 'en', 'title' => 'Development'],
                    ['lang_code' => 'hi', 'title' => 'विकास'],
                    ['lang_code' => 'ar', 'title' => 'تطوير'],
                ],
            ],
        ];
        foreach ($dummyCategories as $item) {
            $category = new BlogCategory();
            $category->slug = Str::slug($item['translations'][0]['title']);
            $category->save();

            foreach ($item['translations'] as $translattion) {
                $categoryTranslation = new BlogCategoryTranslation();
                $categoryTranslation->blog_category_id = $category->id;
                $categoryTranslation->lang_code = $translattion['lang_code'];
                $categoryTranslation->title = $translattion['title'];
                $categoryTranslation->save();
            }
        }

        $description = [
            'en' => "<p>BaseCreate is pleased to announce that it has been commissioned by Leighton Asia reposition its brand. We will help Leighton Asia evolve its brand strategy, and will be responsible updating Leighton Asia&rsquo;s brand identity, website, and other collaterals.</p>
            <p>For almost 50 years Leighton Asia, one of the region&rsquo;s largest and most respected construction companies, has been progressively building for a better future by leveraging international expertise with local intelligence. In that time Leighton has delivered some of Asia&rsquo;s prestigious buildings and transformational infrastructure projects.</p><p>Leighton Asia&rsquo;s brand refreshment will help position the company to meet the challenges of future, as it seeks to lead the industry in technological innovation and sustainable building practices to deliver long-lasting value for its clients.</p><p>But in order that you may see whence all this born error of those who accuse pleasure and praise pain, I will open the whole matter, and explain the very things which  were said by that discoverer of truth and, as it were, the architect of a happy life.</p><p>Always ready to push the boundaries, especially when it comes to our own platform maximum analytical eye to create a site that was visually engaging and also optimised</p>

            <img src='uploads/custom-images/descirption_blog_details_01.jpg'>
            <img src='uploads/custom-images/descirption_blog_details_02.jpg'}>
            
            <p>But in order that you may see whence and praise pain, I will open the whole matter, and explain the very things which were said by that discoverer of truth and, as it were, the architect of a happy life.</p><p>Always ready to push the boundaries, especially when it comes to our own platform maximum analytical eye to create a site that was visually engaging and also optimised</p>",

            'hi' => 'बेसक्रिएट को यह घोषणा करते हुए खुशी हो रही है कि लीटन एशिया ने उसे अपने ब्रांड को फिर से स्थापित करने का काम सौंपा है। हम लीटन एशिया को अपनी ब्रांड रणनीति विकसित करने में मदद करेंगे, और लीटन एशिया की ब्रांड पहचान, वेबसाइट और अन्य सहायक उपकरणों को अपडेट करने के लिए जिम्मेदार होंगे।\\\\लगभग 50 वर्षों से लीटन एशिया, क्षेत्र की सबसे बड़ी और सबसे प्रतिष्ठित निर्माण कंपनियों में से एक है, जो स्थानीय बुद्धिमत्ता के साथ अंतरराष्ट्रीय विशेषज्ञता का लाभ उठाकर बेहतर भविष्य के लिए उत्तरोत्तर निर्माण कर रही है। उस समय में लीटन ने एशिया की कुछ प्रतिष्ठित इमारतों और परिवर्तनकारी बुनियादी ढाँचे की परियोजनाओं को पूरा किया है।',

            'ar' => 'هذا هو التصور الذي يشجع السلة على أن تعود ليتون آسيا علامتها التجارية الخاصة بها مرة أخرى تم التثبيت بسهولة. نحن نعمل على تطوير علامتنا التجارية الخاصة بـ Liten Asia من خلال شراكتنا مع العلامة التجارية Liten Asia. يتم تعديل الكتب والمواقع الإلكترونية وغيرها من الأدوات المساعدة من أجل تعديلها.\\\\منذ 50 عامًا من ليتون آسيا، هي أكبر شركات البناء وأكثرها شهرة في المنطقة إنه أمر أفضل للمساهمين ذوي الخبرة العالمية ك يتم حاليًا تصنيعها في الخارج. في ذلك الوقت، حصل ليتون على بعض المساكن المرخصة والمخصصة لآسيا تم الانتهاء من الشروط بالكامل.',
        ];

        //Blogs
        $dummyBlogs = [
            [
                'image'        => 'uploads/custom-images/blog_1_1.webp',
                'tags'         => '[{"value":"branding"},{"value":"business"}]',
                'translations' => [
                    [
                        'lang_code'   => 'en',
                        'title'       => 'Everything You Should Know About Return',
                    ],
                    [
                        'lang_code'   => 'hi',
                        'title'       => 'रिटर्न के बारे में आपको जो कुछ भी जानना चाहिए',
                    ],
                    [
                        'lang_code'   => 'ar',
                        'title'       => 'كل ما يجب أن تعرفه عن الإرجاع',
                    ],
                ],
            ],
            [
                'image'        => 'uploads/custom-images/blog_1_2.webp',
                'tags'         => '[{"value":"design"},{"value":"commerce"}]',
                'translations' => [
                    [
                        'lang_code'   => 'en',
                        'title'       => '6 Big Commerce Design Tips For Big Results',
                    ],
                    [
                        'lang_code'   => 'hi',
                        'title'       => 'बड़े परिणामों के लिए 6 बड़े वाणिज्य डिजाइन युक्तियाँ',
                    ],
                    [
                        'lang_code'   => 'ar',
                        'title'       => '6 نصائح لتصميم التجارة الكبرى لتحقيق نتائج كبيرة',
                    ],
                ],
            ],
            [
                'image'        => 'uploads/custom-images/blog_1_3.webp',
                'tags'         => '[{"value":"design"},{"value":"business"}]',
                'translations' => [
                    [
                        'lang_code'   => 'en',
                        'title'       => 'Four Steps to Conduct a Successful Usability',
                    ],
                    [
                        'lang_code'   => 'hi',
                        'title'       => 'सफल प्रयोज्यता के संचालन के लिए चार कदम',
                    ],
                    [
                        'lang_code'   => 'ar',
                        'title'       => 'أربع خطوات لإجراء قابلية استخدام ناجحة',
                    ],
                ],
            ],
            [
                'image'        => 'uploads/custom-images/blog_1_4.webp',
                'tags'         => '[{"value":"development"},{"value":"software"},{"value":"ai"}]',
                'translations' => [
                    [
                        'lang_code'   => 'en',
                        'title'       => 'The Future of AI in Software Development',
                    ],
                    [
                        'lang_code'   => 'hi',
                        'title'       => 'सॉफ्टवेयर विकास में एआई का भविष्य',
                    ],
                    [
                        'lang_code'   => 'ar',
                        'title'       => 'مستقبل الذكاء الاصطناعي في تطوير البرمجيات',
                    ],
                ],
            ],
            [
                'image'        => 'uploads/custom-images/blog_1_5.webp',
                'tags'         => '[{"value":"development"},{"value":"software"}]',
                'translations' => [
                    [
                        'lang_code'   => 'en',
                        'title'       => 'How to Optimize Your Software Development Lifecycle',
                    ],
                    [
                        'lang_code'   => 'hi',
                        'title'       => 'अपने सॉफ्टवेयर विकास जीवनचक्र को कैसे अनुकूलित करें',
                    ],
                    [
                        'lang_code'   => 'ar',
                        'title'       => 'كيفية تحسين دورة حياة تطوير البرمجيات الخاصة بك',
                    ],
                ],
            ],
            [
                'image'        => 'uploads/custom-images/blog_1_6.webp',
                'tags'         => '[{"value":"business"},{"value":"branding"}]',
                'translations' => [
                    [
                        'lang_code'   => 'en',
                        'title'       => 'Unlock Your Business Potential',
                    ],
                    [
                        'lang_code'   => 'hi',
                        'title'       => 'अपने व्यवसाय की संभावनाओं को अनलॉक करें',
                    ],
                    [
                        'lang_code'   => 'ar',
                        'title'       => 'أطلق العنان لإمكانات عملك',
                    ],
                ],
            ],
        ];

        foreach ($dummyBlogs as $value) {
            $blog = new Blog();
            $blog->admin_id = Admin::inRandomOrder()->first()->id ?? 1;
            $blog->blog_category_id = BlogCategory::inRandomOrder()->first()->id ?? 1;
            $blog->slug = Str::slug($value['translations'][0]['title']);
            $blog->image = $value['image'];
            $blog->views = $faker->numberBetween(0, 400);
            $blog->show_homepage = true;
            $blog->is_popular = $faker->boolean;
            $blog->tags = $value['tags'];
            $blog->status = true;

            $blog->save();

            foreach ($value['translations'] as $data) {
                $blogTranslation = new BlogTranslation();
                $blogTranslation->blog_id = $blog->id;
                $blogTranslation->lang_code = $data['lang_code'];
                $blogTranslation->title = $data['title'];
                $blogTranslation->description = $description[$data['lang_code']];
                $blogTranslation->seo_title = $data['title'];
                $blogTranslation->seo_description = $faker->paragraph;
                $blogTranslation->save();
            }

            for ($j = 0; $j < 3; $j++) {
                $comment = new BlogComment();
                $comment->user_id = User::inRandomOrder()->first()->id ?? 1;
                $comment->blog_id = $blog->id;
                $comment->name = $faker->name;
                $comment->email = $faker->email;
                $comment->phone = $faker->phoneNumber;
                $comment->comment = $faker->paragraph;
                $comment->image = 'uploads/website-images/default-avatar.png';
                $comment->status = 1;
                $comment->save();
            }
        }

    }
}