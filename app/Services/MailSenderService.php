<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Traits\GlobalMailTrait;
use Illuminate\Support\Facades\Log;
use Modules\GlobalSetting\app\Models\EmailTemplate;

class MailSenderService {
    use GlobalMailTrait;

    public function sendVerifyMailSingleUser($user) {
        try {
            // Log the token being used for email
            Log::info('Sending verification email', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'verification_token' => $user->verification_token,
                'token_length' => strlen($user->verification_token)
            ]);
            
            // Check if email template exists
            $template = EmailTemplate::where('name', 'user_verification')->first();
            if (!$template) {
                Log::error('Email template not found: user_verification');
                throw new Exception('Email template not found');
            }
            
            [$subject, $message] = $this->fetchEmailTemplate('user_verification',['user_name' => $user->name]);
            $link = [__('CONFIRM YOUR EMAIL') => route('user-verification', $user->verification_token)];

            Log::info('Email template fetched successfully', [
                'subject' => $subject,
                'message_length' => strlen($message),
                'link' => $link
            ]);

            $this->sendMail($user->email, $subject, $message, $link);
            
            Log::info('Email sent successfully', [
                'user_email' => $user->email,
                'subject' => $subject
            ]);
        } catch (Exception $e) {
            Log::error('Email sending failed', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
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
