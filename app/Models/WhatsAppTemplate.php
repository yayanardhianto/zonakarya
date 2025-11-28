<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class WhatsAppTemplate extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_templates';

    protected $fillable = [
        'name',
        'type',
        'template',
        'variables',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function replaceVariables($data)
    {
        $message = $this->template;
        foreach ($data as $key => $value) {
            $message = str_replace('{' . strtoupper($key) . '}', $value, $message);
        }
        return $message;
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
        Log::debug('WhatsAppTemplate normalizePhoneNumber input', ['raw' => $phone]);

        // Ensure string
        $phoneStr = (string) $phone;

        // Remove all non-digit characters
        $cleanPhone = preg_replace('/\D+/', '', $phoneStr);

        // If empty after cleaning, return original input
        if ($cleanPhone === null || $cleanPhone === '') {
            Log::debug('WhatsAppTemplate normalizePhoneNumber output', ['result' => $phone]);
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

        Log::debug('WhatsAppTemplate normalizePhoneNumber output', ['result' => $cleanPhone]);

        return $cleanPhone;
    }

    public function generateWhatsAppUrl($phone, $data)
    {
        $message = $this->replaceVariables($data);
        $encodedMessage = urlencode($message);
        $normalizedPhone = $this->normalizePhoneNumber($phone);
        
        return "https://api.whatsapp.com/send/?phone={$normalizedPhone}&text={$encodedMessage}&type=phone_number&app_absent=0";
    }
}