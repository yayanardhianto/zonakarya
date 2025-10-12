<?php

namespace Modules\Testimonial\database\seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Modules\Testimonial\app\Models\Testimonial;
use Modules\Testimonial\app\Models\TestimonialTranslation;

class TestimonialDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $faker = Faker::create();
        $images = [
            [
                'image'   => 'uploads/custom-images/client-1.jpg',
            ],
            [
                'image'   => 'uploads/custom-images/client-2.jpg',
            ],
            [
                'image'   => 'uploads/custom-images/client-3.jpg',
            ],
            [
                'image'   => 'uploads/custom-images/client-4.jpg',
            ],
        ];
        $comments = [
            'en' => "It’s a pleasure working with Bunker. They understood our new brand positioning guidelines and translated them beautifully and consistently into our on-going marketing comms. The team is responsive, quick and always willing help winning partnership",
            'hi' => "बंकर के साथ काम करना खुशी की बात है। उन्होंने हमारे नए ब्रांड पोजिशनिंग दिशानिर्देशों को समझा और उन्हें खूबसूरती से और सुसंगत रूप से हमारी चल रही मार्केटिंग संचार में अनुवादित किया। टीम उत्तरदायी, तेज़ और हमेशा एक सफल साझेदारी में मदद करने के लिए तैयार रहती है।",
            'ar' => "من دواعي سرورنا العمل مع بانكر. لقد فهموا إرشادات تموضع علامتنا التجارية الجديدة وقاموا بترجمتها بشكل جميل ومتسق في اتصالاتنا التسويقية المستمرة. الفريق متجاوب، سريع، ودائمًا على استعداد للمساعدة في تحقيق شراكة ناجحة.",
        ];

        foreach ($images as $dummy) {
            $data = new Testimonial();
            $data->image = $dummy['image'];
            $data->rating = 5;
            $data->status = true;

            if ($data->save()) {
                foreach (allLanguages() as $language) {
                    $dataTranslation = new TestimonialTranslation();
                    $dataTranslation->lang_code = $language->code;
                    $dataTranslation->testimonial_id = $data->id;
                    $dataTranslation->name = $faker->firstName;
                    $dataTranslation->designation = $faker->jobTitle;
                    $dataTranslation->comment = $comments[$language->code];
                    $dataTranslation->save();
                }
            }
        }
    }
}
