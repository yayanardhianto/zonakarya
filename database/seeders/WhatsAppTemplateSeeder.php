<?php

namespace Database\Seeders;

use App\Models\WhatsAppTemplate;
use Illuminate\Database\Seeder;

class WhatsAppTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            // Short Call Invitation Templates
            [
                'name' => 'Short Call Invitation - Standard',
                'type' => 'short_call_invitation',
                'template' => 'Halo {NAME}, selamat! Anda telah lolos tahap screening untuk posisi {POSITION} di {COMPANY}. Kami akan menghubungi Anda untuk tahap short call interview pada {DATE}. Terima kasih!',
                'variables' => ['NAME', 'POSITION', 'COMPANY', 'DATE'],
                'is_active' => true,
            ],
            [
                'name' => 'Short Call Invitation - Professional',
                'type' => 'short_call_invitation',
                'template' => 'Dear {NAME}, Congratulations! You have successfully passed the screening stage for the {POSITION} position at {COMPANY}. We will contact you for a short call interview on {DATE}. Thank you for your interest!',
                'variables' => ['NAME', 'POSITION', 'COMPANY', 'DATE'],
                'is_active' => true,
            ],

            // Group Interview Invitation Templates
            [
                'name' => 'Group Interview Invitation - Standard',
                'type' => 'group_interview_invitation',
                'template' => 'Halo {NAME}, selamat! Anda telah lolos tahap short call untuk posisi {POSITION} di {COMPANY}. Kami mengundang Anda untuk mengikuti Group Interview pada {DATE} pukul {TIME} di {LOCATION}. Silakan konfirmasi kehadiran Anda. Terima kasih!',
                'variables' => ['NAME', 'POSITION', 'COMPANY', 'DATE', 'TIME', 'LOCATION'],
                'is_active' => true,
            ],
            [
                'name' => 'Group Interview Invitation - Professional',
                'type' => 'group_interview_invitation',
                'template' => 'Dear {NAME}, Congratulations! You have successfully passed the short call stage for the {POSITION} position at {COMPANY}. We invite you to attend our Group Interview on {DATE} at {TIME} at {LOCATION}. Please confirm your attendance. Thank you!',
                'variables' => ['NAME', 'POSITION', 'COMPANY', 'DATE', 'TIME', 'LOCATION'],
                'is_active' => true,
            ],

            // Test Psychology Invitation Templates
            [
                'name' => 'Test Psychology Invitation - Standard',
                'type' => 'test_psychology_invitation',
                'template' => 'Halo {NAME}, selamat! Anda telah lolos tahap Group Interview untuk posisi {POSITION} di {COMPANY}. Kami mengundang Anda untuk mengikuti Test Psychology pada {DATE} pukul {TIME} di {LOCATION}. Silakan scan QR Code yang tersedia untuk mengakses test. Terima kasih!',
                'variables' => ['NAME', 'POSITION', 'COMPANY', 'DATE', 'TIME', 'LOCATION'],
                'is_active' => true,
            ],
            [
                'name' => 'Test Psychology Invitation - Professional',
                'type' => 'test_psychology_invitation',
                'template' => 'Dear {NAME}, Congratulations! You have successfully passed the Group Interview stage for the {POSITION} position at {COMPANY}. We invite you to take our Psychology Test on {DATE} at {TIME} at {LOCATION}. Please scan the provided QR Code to access the test. Thank you!',
                'variables' => ['NAME', 'POSITION', 'COMPANY', 'DATE', 'TIME', 'LOCATION'],
                'is_active' => true,
            ],

            // OJT Invitation Templates
            [
                'name' => 'OJT Invitation - Standard',
                'type' => 'ojt_invitation',
                'template' => 'Halo {NAME}, selamat! Anda telah lolos tahap Test Psychology untuk posisi {POSITION} di {COMPANY}. Kami mengundang Anda untuk mengikuti On Job Training (OJT) mulai {START_DATE} hingga {END_DATE} di {LOCATION}. Silakan konfirmasi kehadiran Anda. Terima kasih!',
                'variables' => ['NAME', 'POSITION', 'COMPANY', 'START_DATE', 'END_DATE', 'LOCATION'],
                'is_active' => true,
            ],
            [
                'name' => 'OJT Invitation - Professional',
                'type' => 'ojt_invitation',
                'template' => 'Dear {NAME}, Congratulations! You have successfully passed the Psychology Test for the {POSITION} position at {COMPANY}. We invite you to participate in our On Job Training (OJT) program from {START_DATE} to {END_DATE} at {LOCATION}. Please confirm your participation. Thank you!',
                'variables' => ['NAME', 'POSITION', 'COMPANY', 'START_DATE', 'END_DATE', 'LOCATION'],
                'is_active' => true,
            ],

            // Final Interview Invitation Templates
            [
                'name' => 'Final Interview Invitation - Standard',
                'type' => 'final_interview_invitation',
                'template' => 'Halo {NAME}, selamat! Anda telah menyelesaikan OJT dengan baik untuk posisi {POSITION} di {COMPANY}. Kami mengundang Anda untuk mengikuti Final Interview pada {DATE} pukul {TIME} di {LOCATION}. Silakan konfirmasi kehadiran Anda. Terima kasih!',
                'variables' => ['NAME', 'POSITION', 'COMPANY', 'DATE', 'TIME', 'LOCATION'],
                'is_active' => true,
            ],
            [
                'name' => 'Final Interview Invitation - Professional',
                'type' => 'final_interview_invitation',
                'template' => 'Dear {NAME}, Congratulations! You have successfully completed the OJT program for the {POSITION} position at {COMPANY}. We invite you to attend our Final Interview on {DATE} at {TIME} at {LOCATION}. Please confirm your attendance. Thank you!',
                'variables' => ['NAME', 'POSITION', 'COMPANY', 'DATE', 'TIME', 'LOCATION'],
                'is_active' => true,
            ],

            // Test Reminder Invitation Templates
            [
                'name' => 'Test Reminder - Standard',
                'type' => 'test_reminder_invitation',
                'template' => 'Halo {NAME}, ini adalah pengingat bahwa Anda masih memiliki test screening untuk posisi {POSITION} di {COMPANY} yang belum diselesaikan. Silakan kerjakan test Anda di sini: {TEST_LINK}. Terima kasih!',
                'variables' => ['NAME', 'POSITION', 'COMPANY', 'TEST_LINK'],
                'is_active' => true,
            ],
            [
                'name' => 'Test Reminder - Professional',
                'type' => 'test_reminder_invitation',
                'template' => 'Dear {NAME}, This is a friendly reminder that you still have a screening test to complete for the {POSITION} position at {COMPANY}. Please complete your test at: {TEST_LINK}. Thank you!',
                'variables' => ['NAME', 'POSITION', 'COMPANY', 'TEST_LINK'],
                'is_active' => true,
            ],

            // Rejection Message Templates
            [
                'name' => 'Rejection Message - Standard',
                'type' => 'rejection_message',
                'template' => 'Halo {NAME}, terima kasih telah melamar posisi {POSITION} di {COMPANY}. Setelah mempertimbangkan dengan seksama, kami memutuskan untuk tidak melanjutkan proses rekrutmen. {REASON} Kami berharap Anda sukses di kesempatan lainnya.',
                'variables' => ['NAME', 'POSITION', 'COMPANY', 'REASON'],
                'is_active' => true,
            ],
            [
                'name' => 'Rejection Message - Professional',
                'type' => 'rejection_message',
                'template' => 'Dear {NAME}, Thank you for your interest in the {POSITION} position at {COMPANY}. After careful consideration, we have decided not to move forward with your application. {REASON} We wish you the best in your future endeavors.',
                'variables' => ['NAME', 'POSITION', 'COMPANY', 'REASON'],
                'is_active' => true,
            ],

            // Additional Rejection Templates for Different Stages
            [
                'name' => 'Rejection After Short Call - Standard',
                'type' => 'rejection_message',
                'template' => 'Halo {NAME}, terima kasih telah mengikuti short call interview untuk posisi {POSITION} di {COMPANY}. Setelah evaluasi, kami memutuskan untuk tidak melanjutkan ke tahap berikutnya. {REASON} Kami berharap Anda sukses di kesempatan lainnya.',
                'variables' => ['NAME', 'POSITION', 'COMPANY', 'REASON'],
                'is_active' => true,
            ],
            [
                'name' => 'Rejection After Group Interview - Standard',
                'type' => 'rejection_message',
                'template' => 'Halo {NAME}, terima kasih telah mengikuti Group Interview untuk posisi {POSITION} di {COMPANY}. Setelah evaluasi, kami memutuskan untuk tidak melanjutkan ke tahap berikutnya. {REASON} Kami berharap Anda sukses di kesempatan lainnya.',
                'variables' => ['NAME', 'POSITION', 'COMPANY', 'REASON'],
                'is_active' => true,
            ],
            [
                'name' => 'Rejection After Test Psychology - Standard',
                'type' => 'rejection_message',
                'template' => 'Halo {NAME}, terima kasih telah mengikuti Test Psychology untuk posisi {POSITION} di {COMPANY}. Setelah evaluasi, kami memutuskan untuk tidak melanjutkan ke tahap berikutnya. {REASON} Kami berharap Anda sukses di kesempatan lainnya.',
                'variables' => ['NAME', 'POSITION', 'COMPANY', 'REASON'],
                'is_active' => true,
            ],
            [
                'name' => 'Rejection After OJT - Standard',
                'type' => 'rejection_message',
                'template' => 'Halo {NAME}, terima kasih telah mengikuti OJT untuk posisi {POSITION} di {COMPANY}. Setelah evaluasi, kami memutuskan untuk tidak melanjutkan ke tahap berikutnya. {REASON} Kami berharap Anda sukses di kesempatan lainnya.',
                'variables' => ['NAME', 'POSITION', 'COMPANY', 'REASON'],
                'is_active' => true,
            ],
            [
                'name' => 'Rejection After Final Interview - Standard',
                'type' => 'rejection_message',
                'template' => 'Halo {NAME}, terima kasih telah mengikuti Final Interview untuk posisi {POSITION} di {COMPANY}. Setelah evaluasi, kami memutuskan untuk tidak melanjutkan ke tahap berikutnya. {REASON} Kami berharap Anda sukses di kesempatan lainnya.',
                'variables' => ['NAME', 'POSITION', 'COMPANY', 'REASON'],
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            WhatsAppTemplate::create($template);
        }
    }
}