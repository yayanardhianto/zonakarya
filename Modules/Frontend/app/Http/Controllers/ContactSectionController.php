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
            ]
        );
        cache()->forget('contact_section');

        return $this->redirectWithMessage( RedirectType::UPDATE->value);
    }
}
