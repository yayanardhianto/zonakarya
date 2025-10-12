<?php

namespace Modules\NewsLetter\app\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\GlobalMailTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\NewsLetter\app\Models\NewsLetter;

class NewsLetterController extends Controller {
    use GlobalMailTrait;
    public function newsletter_request(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:news_letters',
        ], [
            'email.required' => __('Email is required'),
            'email.unique'   => __('Email already exist'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 422);
        }
        try {
            DB::beginTransaction();
            $newsletter = new NewsLetter();
            $newsletter->email = $request->email;
            $newsletter->verify_token = Str::random(100);
            $newsletter->save();

            [$subject, $message] = $this->fetchEmailTemplate('subscribe_notification');
            $link = [__('CONFIRM YOUR EMAIL') => route('newsletter-verification', $newsletter->verify_token)];

            $this->sendMail($newsletter->email, $subject, $message, $link);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('A verification link has been sent to your email. Please verify it to start receiving our newsletter.'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            info($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Mail sending operation failed. Please try again.'),
            ]);
        }

    }

    public function newsletter_verification($token) {
        $newsletter = NewsLetter::where('verify_token', $token)->first();

        if ($newsletter) {
            $newsletter->verify_token = null;
            $newsletter->status = 'verified';
            $newsletter->save();

            $notification = __('Newsletter verification successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->route('home')->with($notification);

        } else {
            $notification = __('Newsletter verification failed for invalid token');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->route('home')->with($notification);
        }

    }
}
