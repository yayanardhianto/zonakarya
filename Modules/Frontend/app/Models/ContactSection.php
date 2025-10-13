<?php

namespace Modules\Frontend\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSection extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'address', 'phone', 'phone_two', 'email', 'email_two', 'map',
        'headquarters_title', 'email_title', 'phone_title',
        'get_direction_text', 'send_message_text', 'call_anytime_text',
        'form_title', 'form_subtitle', 'full_name_label', 'email_label',
        'website_label', 'subject_label', 'message_label', 'submit_button_text',
        'page_title', 'breadcrumb_title',
        'show_website_field', 'show_second_phone', 'show_second_email'
    ];
}
