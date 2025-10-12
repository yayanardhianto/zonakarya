<?php

namespace Modules\OurTeam\app\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\GetGlobalInformationTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\GlobalSetting\app\Models\EmailTemplate;
use Modules\ContactMessage\app\Emails\ContactMessageMail;

class ContactTeamMemberMessageSendJob implements ShouldQueue
{
    use Dispatchable, GetGlobalInformationTrait, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private $contact_message, private $member_email)
    {
        $this->contact_message = $contact_message;
        $this->member_email = $member_email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->set_mail_config();

        try {
            $template = EmailTemplate::where('name', 'contact_mail')->first();
            $subject = $template->subject;
            $message = $template->message;
            $message = str_replace('{{name}}', $this->contact_message->name, $message);
            $message = str_replace('{{email}}', $this->contact_message->email, $message);
            $message = str_replace('{{phone}}', $this->contact_message->phone, $message);
            $message = str_replace('{{subject}}', $this->contact_message->subject, $message);
            $message = str_replace('{{message}}', $this->contact_message->message, $message);

            Mail::to($this->member_email)->send(new ContactMessageMail($subject, $message));
        } catch (Exception $e) {
            info($e->getMessage());
        }
    }
}
