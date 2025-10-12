<?php

namespace Modules\ContactMessage\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Rules\CustomRecaptcha;
use App\Traits\GlobalMailTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\ContactMessage\app\Models\ContactMessage;

class ContactMessageController extends Controller {
    use GlobalMailTrait;
    public function store(Request $request) {
        $setting = cache()->get('setting');
        $validator = Validator::make($request->all(), [
            'name'                 => 'required',
            'email'                => 'required',
            'website'              => 'sometimes',
            'subject'              => 'required',
            'message'              => 'required',
            'g-recaptcha-response' => $setting?->recaptcha_status == 'active' ? ['required', new CustomRecaptcha()] : '',
        ], [
            'name.required'                 => __('Name is required'),
            'email.required'                => __('Email is required'),
            'subject.required'              => __('Subject is required'),
            'message.required'              => __('Message is required'),
            'g-recaptcha-response.required' => __('Please complete the recaptcha to submit the form'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }
        $new_message = new ContactMessage();
        $new_message->name = $request->name;
        $new_message->email = $request->email;
        $new_message->website = $request->website;
        $new_message->subject = $request->subject;
        $new_message->message = $request->message;
        $new_message->save();

        try {
            $str_replace = [
                'name'    => $new_message->name,
                'email'   => $new_message->email,
                'website' => $new_message->website,
                'subject' => $new_message->subject,
                'message' => $new_message->message,
            ];
            [$subject, $message] = $this->fetchEmailTemplate('contact_mail', $str_replace);
            $this->sendMail($setting->contact_message_receiver_mail, $subject, $message);
        } catch (\Exception $e) {
            info($e->getMessage());
        }
        return response()->json([
            'success' => true,
            'message' => __('Message Sent Successfully'),
        ]);
    }
}
