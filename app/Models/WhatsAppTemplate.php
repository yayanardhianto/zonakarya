<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        // Remove all non-numeric characters
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        
        // If empty, return as is
        if (empty($cleanPhone)) {
            return $phone;
        }
        
        // If starts with 0, replace with 62
        if (substr($cleanPhone, 0, 1) === '0') {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }
        // If starts with 62, keep it
        elseif (substr($cleanPhone, 0, 2) === '62') {
            // Already in correct format
        }
        // If doesn't start with 62, assume it's local format and add 62
        else {
            $cleanPhone = '62' . $cleanPhone;
        }
        
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