<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantStatusController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all applications for this user through their applicants
        $applications = Application::whereHas('applicant', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with([
            'applicant',
            'jobVacancy' => function($query) {
                $query->select('id', 'position', 'company_name', 'location', 'created_at');
            },
            'testSession' => function($query) {
                $query->select('id', 'status', 'access_token', 'expires_at');
            }
        ])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('frontend.user.applications.index', compact('applications'));
    }

    public function show(Application $application)
    {
        $user = Auth::user();
        
        // Ensure the application belongs to this user
        if ($application->applicant->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this application.');
        }

        $application->load([
            'applicant',
            'jobVacancy' => function($query) {
                $query->select('id', 'position', 'company_name', 'location', 'description', 'specific_requirements', 'benefits', 'created_at');
            },
            'testSession' => function($query) {
                $query->select('id', 'status', 'access_token', 'expires_at', 'created_at');
            }
        ]);

        return view('frontend.user.applications.show', compact('application'));
    }
}