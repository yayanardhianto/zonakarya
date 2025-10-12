<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WhatsAppTemplate;

class OfferingLetterTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Offering Letter - Standard',
                'type' => 'offering_letter_invitation',
                'template' => 'Halo {NAME}, selamat! 🎉

Kami dengan senang hati menginformasikan bahwa Anda telah berhasil melewati semua tahapan seleksi untuk posisi {POSITION} di {COMPANY}.

📋 **DETAIL OFFER:**
• Posisi: {POSITION}
• Gaji: {SALARY}
• Mulai kerja: {START_DATE}
• Lokasi: {LOCATION}

📄 **DOKUMEN YANG PERLU DISIAPKAN:**
• Surat lamaran kerja
• CV terbaru
• Fotokopi KTP
• Fotokopi ijazah
• Pas foto 3x4 (2 lembar)
• Surat keterangan sehat

Silakan konfirmasi penerimaan offer ini dan siapkan dokumen yang diperlukan. Kami akan mengirimkan surat penawaran resmi melalui email.

Terima kasih dan selamat bergabung! 🚀

Hormat kami,
Tim HR {COMPANY}',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Offering Letter - Professional',
                'type' => 'offering_letter_invitation',
                'template' => 'Dear {NAME},

Congratulations! 🎊

We are pleased to inform you that you have successfully passed all selection stages for the position of {POSITION} at {COMPANY}.

📋 **OFFER DETAILS:**
• Position: {POSITION}
• Salary: {SALARY}
• Start Date: {START_DATE}
• Location: {LOCATION}

📄 **REQUIRED DOCUMENTS:**
• Job application letter
• Updated CV
• ID card copy
• Diploma copy
• 3x4 photo (2 pieces)
• Health certificate

Please confirm your acceptance of this offer and prepare the required documents. We will send the official offer letter via email.

Thank you and welcome aboard! 🚀

Best regards,
HR Team {COMPANY}',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Offering Letter - Formal',
                'type' => 'offering_letter_invitation',
                'template' => 'Kepada Yth. {NAME}

Dengan hormat,

Berdasarkan hasil seleksi yang telah dilaksanakan, kami dengan bangga mengumumkan bahwa Anda telah diterima untuk mengisi posisi {POSITION} di {COMPANY}.

**RINCIAN PENAWARAN:**
• Jabatan: {POSITION}
• Gaji Pokok: {SALARY}
• Tanggal Mulai: {START_DATE}
• Tempat Kerja: {LOCATION}

**DOKUMEN YANG HARUS DISERAHKAN:**
1. Surat lamaran kerja
2. Curriculum Vitae (CV) terbaru
3. Fotokopi KTP yang masih berlaku
4. Fotokopi ijazah dan transkrip nilai
5. Pas foto 3x4 sebanyak 2 lembar
6. Surat keterangan sehat dari dokter
7. Surat keterangan bebas narkoba

Mohon konfirmasi penerimaan penawaran ini dalam waktu 3 hari kerja. Surat penawaran resmi akan dikirimkan melalui email.

Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.

Hormat kami,
Tim Human Resources
{COMPANY}',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($templates as $template) {
            WhatsAppTemplate::create($template);
        }

        $this->command->info('Offering Letter templates created successfully!');
    }
}