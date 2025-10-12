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

    public function generateWhatsAppUrl($phone, $data)
    {
        $message = $this->replaceVariables($data);
        $encodedMessage = urlencode($message);
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        
        return "https://api.whatsapp.com/send/?phone={$cleanPhone}&text={$encodedMessage}&type=phone_number&app_absent=0";
    }
}