<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Traits\GlobalMailTrait;
use Illuminate\Support\Facades\Log;

class MailSenderService {
    use GlobalMailTrait;

    public function sendVerifyMailSingleUser($user) {
        try {
            [$subject, $message] = $this->fetchEmailTemplate('user_verification',['user_name' => $user->name]);
            $link = [__('CONFIRM YOUR EMAIL') => route('user-verification', $user->verification_token)];

            $this->sendMail($user->email, $subject, $message, $link);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }

    public function sendVerifyMailToAllUser() {
        try {
            $users = User::where('email_verified_at', null)->orderBy('id', 'desc')->get();
            foreach ($users as $user) {
                $user->verification_token = \Illuminate\Support\Str::random(100);
                $user->save();

                [$subject, $message] = $this->fetchEmailTemplate('user_verification',['user_name' => $user->name]);

                $link = [__('CONFIRM YOUR EMAIL') => route('user-verification', $user->verification_token)];
                $message = str_replace('{{user_name}}', $user->name, $message);

                $this->sendMail($user->email, $subject, $message, $link);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }

    public function SendBulkEmail($email_list, $mail_subject, $mail_message) {
        try {
            foreach ($email_list as $email) {
                $this->sendMail($email->email, $mail_subject, $mail_message);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}
