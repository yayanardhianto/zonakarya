<?php

namespace Modules\NewsLetter\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MailSenderService;
use Illuminate\Http\Request;
use Modules\NewsLetter\app\Models\NewsLetter;

class NewsLetterController extends Controller {
    public function index() {
        checkAdminHasPermissionAndThrowException('newsletter.view');
        $newsletters = NewsLetter::orderBy('id', 'desc')->where('status', 'verified')->get();

        return view('newsletter::index', ['newsletters' => $newsletters]);
    }

    public function create() {
        checkAdminHasPermissionAndThrowException('newsletter.mail');
        return view('newsletter::create');
    }

    public function destroy($id) {
        checkAdminHasPermissionAndThrowException('newsletter.delete');
        $newsletter = NewsLetter::find($id);
        $newsletter->delete();

        $notification = __('Deleted successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function store(Request $request) {
        checkAdminHasPermissionAndThrowException('newsletter.mail');
        $request->validate([
            'subject'     => 'required',
            'description' => 'required',
        ], [
            'subject.required'     => __('Subject is required'),
            'description.required' => __('Description is required'),
        ]);

        $newsletterCount = NewsLetter::select('id')->orderBy('id', 'desc')->where('status', 'verified')->count();

        if ($newsletterCount > 0) {
            $email_list = NewsLetter::select('email')->orderBy('id', 'desc')->where('status', 'verified')->get();
            (new MailSenderService)->SendBulkEmail($email_list, $request->subject, $request->description);

            $notification = __('Mail Sent Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];
        } else {
            $notification = __('The email cannot be sent because no subscribers were found.');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
        }

        return redirect()->back()->with($notification);
    }
}
