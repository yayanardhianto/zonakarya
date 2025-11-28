<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private $apiUrl;
    private $apiKey;
    private $phoneNumber;

    public function __construct()
    {
        // TODO: Get from settings
        $this->apiUrl = 'https://api.whatsapp.com/send';
        $this->apiKey = 'dummy_api_key';
        $this->phoneNumber = '6281234567890'; // Dummy phone number
    }

    /**
     * Send WhatsApp message using Eva API
     */
    public function sendMessage($to, $message)
    {
        try {
            $url = "https://whook2.eva.id/kirim_pesan/";
            
            // Normalize phone number to international format (62xxxxxxxxxx)
            $cleanPhone = $this->normalizePhoneNumber($to);
            
            $messageData = [
                'eva_email' => 'banksat5@yahoo.com',
                'recipient' => $cleanPhone,
                'platform_id' => "22",
                'msg_type' => "1",
                'msg_data' => $message,
                'outtype' => "1",
                'outcontent' => $message,
                'msg_mode' => "push"
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($messageData))
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            // Log the API call
            Log::info('WhatsApp API Call:', [
                'to' => $cleanPhone,
                'message' => $message,
                'http_code' => $httpCode,
                'response' => $response,
                'timestamp' => now()
            ]);

            if ($curlError) {
                throw new \Exception('CURL Error: ' . $curlError);
            }

            if ($httpCode !== 200) {
                throw new \Exception('HTTP Error: ' . $httpCode . ' - ' . $response);
            }

            $responseData = json_decode($response, true);
            
            return [
                'success' => true,
                'message_id' => isset($responseData['message_id']) ? $responseData['message_id'] : 'eva_' . time(),
                'status' => 'sent',
                'response' => $responseData
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp API Error:', [
                'error' => $e->getMessage(),
                'to' => $to,
                'message' => $message,
                'timestamp' => now()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send test invitation message
     */
    public function sendTestInvitation($applicant, $testUrl)
    {
        $application = $applicant->applications()->latest()->first();
        $position = $application ? $application->jobVacancy->position : 'Posisi yang dilamar';
        
        $message = "Halo {$applicant->name}, terima kasih telah melamar posisi {$position}.

        Silakan ikuti test screening di link berikut:
        {$testUrl}";
                
        return $this->sendMessage($applicant->whatsapp, $message);
    }

    /**
     * Send short call invitation message (Manual - using template)
     */
    public function sendShortCallInvitation($applicant)
    {
        // This method is kept for backward compatibility
        // But short call invitations should be sent manually using templates
        $application = $applicant->applications()->latest()->first();
        $position = $application ? $application->jobVacancy->position : 'Posisi yang dilamar';
        $name = $applicant->name;
        
        $message = "Halo {$name}, selamat! Anda telah lolos tahap screening untuk posisi {$position}. Kami akan menghubungi Anda untuk tahap short call. Terima kasih!";
        
        // Log for manual sending
        Log::info('Short Call Invitation (Manual):', [
            'applicant' => $applicant->name,
            'whatsapp' => $applicant->whatsapp,
            'message' => $message,
            'timestamp' => now()
        ]);
        
        return [
            'success' => true,
            'message' => 'Please send WhatsApp manually using template',
            'manual_url' => $this->generateWebLink($applicant->whatsapp, $message)
        ];
    }

    /**
     * Send rejection message (Manual - using template)
     */
    public function sendRejectionMessage($applicant, $reason = null)
    {
        // This method is kept for backward compatibility
        // But rejection messages should be sent manually using templates
        $application = $applicant->applications()->latest()->first();
        $position = $application ? $application->jobVacancy->position : 'Posisi yang dilamar';
        $name = $applicant->name;
        
        $reasonText = $reason ? 'Alasan: ' . $reason : '';
        $message = "Halo {$name}, terima kasih telah melamar posisi {$position}. Setelah mempertimbangkan dengan seksama, kami memutuskan untuk tidak melanjutkan proses rekrutmen. {$reasonText}";
        
        // Log for manual sending
        Log::info('Rejection Message (Manual):', [
            'applicant' => $applicant->name,
            'whatsapp' => $applicant->whatsapp,
            'message' => $message,
            'reason' => $reason,
            'timestamp' => now()
        ]);
        
        return [
            'success' => true,
            'message' => 'Please send WhatsApp manually using template',
            'manual_url' => $this->generateWebLink($applicant->whatsapp, $message)
        ];
    }

    /**
     * Normalize phone number to international format (62xxxxxxxxxx)
     * Converts: 081234567890 -> 6281234567890
     * Converts: +6281234567890 -> 6281234567890
     * Converts: 6281234567890 -> 6281234567890
     */
    private function normalizePhoneNumber($phone)
    {
        // Log raw input for debugging
        Log::debug('normalizePhoneNumber input', ['raw' => $phone]);

        // Ensure we have a string
        $phoneStr = (string) $phone;

        // Remove all non-digit characters (safer: \D)
        $cleanPhone = preg_replace('/\D+/', '', $phoneStr);

        // If empty after cleaning, return original input
        if ($cleanPhone === null || $cleanPhone === '') {
            Log::debug('normalizePhoneNumber output', ['result' => $phone]);
            return $phone;
        }

        // Normalize rules:
        // - starts with '0'  => replace leading 0 with 62
        // - starts with '62' => keep
        // - starts with '8'  => add 62 (local without leading zero)
        // - starts with other => add 62 as fallback

        if (strpos($cleanPhone, '0') === 0) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        } elseif (strpos($cleanPhone, '62') === 0) {
            // already good
        } elseif (strpos($cleanPhone, '8') === 0) {
            $cleanPhone = '62' . $cleanPhone;
        } else {
            $cleanPhone = '62' . $cleanPhone;
        }

        // Log the normalized result
        Log::debug('normalizePhoneNumber output', ['result' => $cleanPhone]);

        return $cleanPhone;
    }

    /**
     * Generate WhatsApp web link
     */
    public function generateWebLink($phone, $message)
    {
        $normalizedPhone = $this->normalizePhoneNumber($phone);
        $encodedMessage = urlencode($message);
        return "https://wa.me/{$normalizedPhone}?text={$encodedMessage}";
    }

    /**
     * Get message templates
     */
    public function getTemplates()
    {
        return [
            'test_invitation' => [
                'name' => 'Test Invitation',
                'template' => 'Halo {NAME}, terima kasih telah melamar posisi {POSITION}. Silakan ikuti test screening di: {TEST_URL}',
                'variables' => ['NAME', 'POSITION', 'TEST_URL']
            ],
            'short_call_invitation' => [
                'name' => 'Short Call Invitation',
                'template' => 'Halo {NAME}, selamat! Anda telah lolos tahap screening untuk posisi {POSITION}. Kami akan menghubungi Anda untuk tahap short call. Terima kasih!',
                'variables' => ['NAME', 'POSITION']
            ],
            'rejection' => [
                'name' => 'Rejection Message',
                'template' => 'Halo {NAME}, terima kasih telah melamar posisi {POSITION}. Setelah mempertimbangkan dengan seksama, kami memutuskan untuk tidak melanjutkan proses rekrutmen. {REASON}',
                'variables' => ['NAME', 'POSITION', 'REASON']
            ]
        ];
    }

    /**
     * Replace template variables
     */
    public function replaceTemplateVariables($template, $variables)
    {
        $message = $template;
        foreach ($variables as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }
        return $message;
    }

    /**
     * Send waiting notification (tanpa URL test)
     */
    public function sendWaitingNotification($applicant)
    {
        $message = "Halo {$applicant->name}, aplikasi Anda telah diterima. " .
                   "Kami sedang memproses hasil test screening Anda. " .
                   "Mohon tunggu informasi selanjutnya dari tim HR kami.";
        
        return $this->sendMessage($applicant->whatsapp, $message);
    }
}
