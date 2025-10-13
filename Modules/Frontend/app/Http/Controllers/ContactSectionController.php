<?php

namespace Modules\Frontend\app\Http\Controllers;

use App\Enums\RedirectType;
use Illuminate\Http\Request;
use App\Traits\RedirectHelperTrait;
use App\Http\Controllers\Controller;
use Modules\Frontend\app\Models\ContactSection;

class ContactSectionController extends Controller {
    use RedirectHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index() {
        checkAdminHasPermissionAndThrowException('section.management');
        $contact = ContactSection::first();
        return view('frontend::contact-section', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request) {
        checkAdminHasPermissionAndThrowException('section.management');

        $validated = $request->validate([
            'address' => ['nullable', 'string', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:255'],
            'phone_two'   => ['nullable', 'string', 'max:255'],
            'email'   => ['nullable', 'string', 'email', 'max:255'],
            'email_two'   => ['nullable', 'string', 'email', 'max:255'],
            'map'     => ['nullable', 'string'],
            // Wording fields
            'headquarters_title' => ['nullable', 'string', 'max:255'],
            'email_title' => ['nullable', 'string', 'max:255'],
            'phone_title' => ['nullable', 'string', 'max:255'],
            'get_direction_text' => ['nullable', 'string', 'max:255'],
            'send_message_text' => ['nullable', 'string', 'max:255'],
            'call_anytime_text' => ['nullable', 'string', 'max:255'],
            'form_title' => ['nullable', 'string', 'max:255'],
            'form_subtitle' => ['nullable', 'string', 'max:500'],
            'full_name_label' => ['nullable', 'string', 'max:255'],
            'email_label' => ['nullable', 'string', 'max:255'],
            'website_label' => ['nullable', 'string', 'max:255'],
            'subject_label' => ['nullable', 'string', 'max:255'],
            'message_label' => ['nullable', 'string', 'max:255'],
            'submit_button_text' => ['nullable', 'string', 'max:255'],
            // Page title fields
            'page_title' => ['nullable', 'string', 'max:255'],
            'breadcrumb_title' => ['nullable', 'string', 'max:255'],
            // Visibility settings
            'show_website_field' => ['sometimes', 'in:on,off,1,0,true,false'],
            'show_second_phone' => ['sometimes', 'in:on,off,1,0,true,false'],
            'show_second_email' => ['sometimes', 'in:on,off,1,0,true,false'],
        ]);
        ContactSection::updateOrCreate(
            ['id' => 1],
            [
                'address'   => $validated['address'],
                'phone' => $validated['phone'],
                'phone_two' => $validated['phone_two'],
                'email' => $validated['email'],
                'email_two' => $validated['email_two'],
                'map'       => 'https://' . $validated['map'],
                // Wording fields
                'headquarters_title' => $validated['headquarters_title'],
                'email_title' => $validated['email_title'],
                'phone_title' => $validated['phone_title'],
                'get_direction_text' => $validated['get_direction_text'],
                'send_message_text' => $validated['send_message_text'],
                'call_anytime_text' => $validated['call_anytime_text'],
                'form_title' => $validated['form_title'],
                'form_subtitle' => $validated['form_subtitle'],
                'full_name_label' => $validated['full_name_label'],
                'email_label' => $validated['email_label'],
                'website_label' => $validated['website_label'],
                'subject_label' => $validated['subject_label'],
                'message_label' => $validated['message_label'],
                'submit_button_text' => $validated['submit_button_text'],
                // Page title fields
                'page_title' => $validated['page_title'],
                'breadcrumb_title' => $validated['breadcrumb_title'],
                // Visibility settings
                'show_website_field' => $request->boolean('show_website_field'),
                'show_second_phone' => $request->boolean('show_second_phone'),
                'show_second_email' => $request->boolean('show_second_email'),
            ]
        );
        cache()->forget('contact_section');

        return $this->redirectWithMessage( RedirectType::UPDATE->value);
    }
}
