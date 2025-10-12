<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobVacancy;

class JobVacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = [
            [
                'position' => 'Senior Web Developer',
                'location' => 'Kantor Pusat Jakarta - Jakarta, DKI Jakarta',
                'location_id' => 1,
                'work_type' => 'Full-Time',
                'salary_min' => 8000000,
                'salary_max' => 15000000,
                'education' => 'S1',
                'gender' => 'Semua Jenis',
                'age_min' => 25,
                'age_max' => 35,
                'experience_years' => 3,
                'specific_requirements' => ['PHP Laravel', 'MySQL', 'JavaScript', 'Vue.js', 'Git'],
                'description' => 'Kami mencari Senior Web Developer yang berpengalaman untuk bergabung dengan tim development kami. Kandidat akan bertanggung jawab untuk mengembangkan dan memelihara aplikasi web yang kompleks menggunakan teknologi modern.',
                'responsibilities' => '• Mengembangkan aplikasi web menggunakan PHP Laravel\n• Mengoptimalkan performa database MySQL\n• Berkolaborasi dengan tim frontend untuk integrasi API\n• Melakukan code review dan mentoring junior developer\n• Memastikan kualitas kode dan best practices',
                'benefits' => '• Gaji kompetitif dan bonus performa\n• Asuransi kesehatan dan BPJS\n• Work from home 2 hari per minggu\n• Training dan sertifikasi\n• Makan siang gratis di kantor',
                'company_name' => 'PT. ATS Company',
                'contact_email' => 'hr@atscompany.com',
                'contact_phone' => '+62-21-1234-5678',
                'status' => 'active',
                'application_deadline' => now()->addDays(30),
            ],
            [
                'position' => 'UI/UX Designer',
                'location' => 'Cabang Bandung - Bandung, Jawa Barat',
                'location_id' => 2,
                'work_type' => 'Full-Time',
                'salary_min' => 6000000,
                'salary_max' => 12000000,
                'education' => 'S1',
                'gender' => 'Semua Jenis',
                'age_min' => 22,
                'age_max' => 30,
                'experience_years' => 2,
                'specific_requirements' => ['Figma', 'Adobe Creative Suite', 'Prototyping', 'User Research', 'Design System'],
                'description' => 'Kami mencari UI/UX Designer kreatif untuk mendesain pengalaman pengguna yang luar biasa. Kandidat akan bekerja sama dengan tim product dan development untuk menciptakan desain yang user-friendly dan menarik.',
                'responsibilities' => '• Mendesain user interface untuk aplikasi web dan mobile\n• Melakukan user research dan usability testing\n• Membuat wireframe dan prototype\n• Berkolaborasi dengan developer untuk implementasi desain\n• Mengembangkan design system dan style guide',
                'benefits' => '• Gaji kompetitif\n• Asuransi kesehatan\n• Flexible working hours\n• Budget untuk tools dan software\n• Learning allowance',
                'company_name' => 'Creative Studio Bandung',
                'contact_email' => 'careers@creativestudio.id',
                'contact_phone' => '+62-22-9876-5432',
                'status' => 'active',
                'application_deadline' => now()->addDays(21),
            ],
            [
                'position' => 'Digital Marketing Specialist',
                'location' => 'Cabang Surabaya - Surabaya, Jawa Timur',
                'location_id' => 3,
                'work_type' => 'Full-Time',
                'salary_min' => 5000000,
                'salary_max' => 10000000,
                'education' => 'S1',
                'gender' => 'Semua Jenis',
                'age_min' => 23,
                'age_max' => 32,
                'experience_years' => 2,
                'specific_requirements' => ['Google Ads', 'Facebook Ads', 'SEO', 'Analytics', 'Content Marketing'],
                'description' => 'Kami mencari Digital Marketing Specialist yang berpengalaman untuk mengelola kampanye digital marketing dan meningkatkan brand awareness perusahaan.',
                'responsibilities' => '• Mengelola kampanye Google Ads dan Facebook Ads\n• Melakukan SEO optimization\n• Membuat konten marketing\n• Menganalisis data dan performance\n• Mengelola social media',
                'benefits' => '• Gaji + komisi\n• Asuransi kesehatan\n• Work from home\n• Training digital marketing\n• Bonus berdasarkan performance',
                'company_name' => 'Digital Agency Surabaya',
                'contact_email' => 'hr@digitalagency.id',
                'contact_phone' => '+62-31-5555-7777',
                'status' => 'active',
                'application_deadline' => now()->addDays(14),
            ],
            [
                'position' => 'Data Analyst',
                'location' => 'Cabang Yogyakarta - Yogyakarta, DI Yogyakarta',
                'location_id' => 4,
                'work_type' => 'Full-Time',
                'salary_min' => 7000000,
                'salary_max' => 13000000,
                'education' => 'S1',
                'gender' => 'Semua Jenis',
                'age_min' => 24,
                'age_max' => 35,
                'experience_years' => 2,
                'specific_requirements' => ['Python', 'SQL', 'Excel', 'Tableau', 'Statistics'],
                'description' => 'Kami mencari Data Analyst yang dapat menganalisis data bisnis dan memberikan insights yang actionable untuk pengambilan keputusan strategis.',
                'responsibilities' => '• Menganalisis data bisnis dan trend\n• Membuat dashboard dan laporan\n• Menggunakan Python dan SQL untuk data processing\n• Berkolaborasi dengan tim untuk kebutuhan data\n• Presentasi hasil analisis ke management',
                'benefits' => '• Gaji kompetitif\n• Asuransi kesehatan dan BPJS\n• Flexible working arrangement\n• Training data science\n• Laptop dan tools provided',
                'company_name' => 'Data Insights Yogyakarta',
                'contact_email' => 'careers@datainsights.id',
                'contact_phone' => '+62-274-1234-5678',
                'status' => 'active',
                'application_deadline' => now()->addDays(28),
            ],
            [
                'position' => 'Mobile App Developer',
                'location' => 'Cabang Bali - Bali, Bali',
                'location_id' => 5,
                'work_type' => 'Contract',
                'salary_min' => 10000000,
                'salary_max' => 18000000,
                'education' => 'S1',
                'gender' => 'Semua Jenis',
                'age_min' => 25,
                'age_max' => 40,
                'experience_years' => 4,
                'specific_requirements' => ['React Native', 'Flutter', 'iOS', 'Android', 'API Integration'],
                'description' => 'Kami mencari Mobile App Developer berpengalaman untuk mengembangkan aplikasi mobile cross-platform yang inovatif dan user-friendly.',
                'responsibilities' => '• Mengembangkan aplikasi mobile menggunakan React Native/Flutter\n• Integrasi dengan backend API\n• Testing dan debugging aplikasi\n• Optimasi performa aplikasi\n• Deployment ke App Store dan Play Store',
                'benefits' => '• Gaji tinggi untuk kontrak\n• Work from anywhere (Bali)\n• Project-based bonus\n• Latest development tools\n• Flexible schedule',
                'company_name' => 'Bali Tech Innovation',
                'contact_email' => 'jobs@balitech.id',
                'contact_phone' => '+62-361-9999-8888',
                'status' => 'active',
                'application_deadline' => now()->addDays(45),
            ]
        ];

        foreach ($jobs as $job) {
            JobVacancy::create($job);
        }
    }
}
