<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Blog\app\Models\Blog;
use App\Http\Controllers\Controller;
use Modules\NewsLetter\app\Models\NewsLetter;

class DashboardController extends Controller {
    public function dashboard(Request $request) {
        $data = [];
        
        // Basic statistics for CMS
        $data['total_user'] = User::select('id')->get();
        $data['total_newsletter'] = NewsLetter::select('id')->get();
        
        // Blog statistics
        if (checkAdminHasPermission('blog.view')) {
            $data['total_blog_posts'] = Blog::select('id')->active()->get();
            $data['total_blog_categories'] = \Modules\Blog\app\Models\BlogCategory::select('id')->active()->get();
            
            $data['latestBlogPosts'] = Blog::with([
                'translation'          => function ($query) {
                    $query->select('blog_id', 'title');
                },
                'category'             => function ($query) {
                    $query->select('id', 'slug');
                },
                'category.translation' => function ($query) {
                    $query->select('blog_category_id', 'title');
                }])->withCount(['comments' => function ($query) {$query->active();}])->active()->latest()->take(5)->get();
        }

        // Team statistics
        if (checkAdminHasPermission('our.team.view')) {
            $data['total_team_members'] = \Modules\OurTeam\app\Models\OurTeam::select('id')->active()->get();
        }

        // Service statistics
        if (checkAdminHasPermission('service.view')) {
            $data['total_services'] = \Modules\Service\app\Models\Service::select('id')->active()->get();
        }

        // Project statistics
        if (checkAdminHasPermission('project.view')) {
            $data['total_projects'] = \Modules\Project\app\Models\Project::select('id')->active()->get();
        }

        // Testimonial statistics
        if (checkAdminHasPermission('testimonial.view')) {
            $data['total_testimonials'] = \Modules\Testimonial\app\Models\Testimonial::select('id')->active()->get();
        }

        // FAQ statistics
        if (checkAdminHasPermission('faq.view')) {
            $data['total_faqs'] = \Modules\Faq\app\Models\Faq::select('id')->active()->get();
        }

        // Contact messages
        if (checkAdminHasPermission('contact.message.view')) {
            $data['total_contact_messages'] = \Modules\ContactMessage\app\Models\ContactMessage::select('id')->get();
            $data['latest_contact_messages'] = \Modules\ContactMessage\app\Models\ContactMessage::latest()->take(5)->get();
        }


        return view('admin.dashboard', $data);
    }

    public function setLanguage() {
        $action = setLanguage(request('code'));

        if ($action) {
            $notification = __('Language Changed Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];
            return redirect()->back()->with($notification);
        }

        $notification = __('Language Changed Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }
}
