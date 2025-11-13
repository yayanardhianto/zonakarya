<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use App\Models\Applicant;
use Illuminate\Http\Request;

class JobVacancyController extends Controller
{
    /**
     * Display a listing of job vacancies
     */
    public function index(Request $request)
    {
        $query = JobVacancy::available();

        // Filter by position
        if ($request->filled('position')) {
            $query->where('position', 'like', '%' . $request->position . '%');
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Filter by work type
        if ($request->filled('work_type')) {
            $query->where('work_type', $request->work_type);
        }

        // Filter by education
        if ($request->filled('education')) {
            $query->where('education', $request->education);
        }

        // Filter by experience
        if ($request->filled('experience')) {
            $query->where('experience_years', '<=', $request->experience);
        }

        $jobs = $query->latest()->paginate(12);

        return view('frontend.job-vacancy.index', compact('jobs'));
    }

    /**
     * Display the specified job vacancy
     */
    public function show(JobVacancy $jobVacancy)
    {
        // Increment views
        $jobVacancy->incrementViews();

        // Get user data if logged in
        $user = auth()->user();
        $applicant = null;
        $hasExistingApplication = false;
        $existingApplication = null;
        
        if ($user) {
            $applicant = \App\Models\Applicant::where('user_id', $user->id)->first();
            
            // Check if user/applicant already applied to this job vacancy
            if ($applicant) {
                $existingApplication = \App\Models\Application::where('applicant_id', $applicant->id)
                    ->where('job_vacancy_id', $jobVacancy->id)
                    ->first();
                
                if ($existingApplication) {
                    $hasExistingApplication = true;
                }
            }
        }

        return view('frontend.job-vacancy.show', compact('jobVacancy', 'user', 'applicant', 'hasExistingApplication', 'existingApplication'));
    }

    /**
     * Search job vacancies
     */
    public function search(Request $request)
    {
        $query = JobVacancy::available();

        // Search functionality
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('position', 'like', '%' . $searchTerm . '%')
                  ->orWhere('company_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('location', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by position
        if ($request->filled('position')) {
            $query->where('position', 'like', '%' . $request->position . '%');
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Filter by work type
        if ($request->filled('work_type')) {
            $query->where('work_type', $request->work_type);
        }

        // Filter by education
        if ($request->filled('education')) {
            $query->where('education', $request->education);
        }

        // Filter by experience
        if ($request->filled('experience')) {
            $query->where('experience_years', '<=', $request->experience);
        }

        $jobs = $query->latest()->paginate(12);

        return view('frontend.job-vacancy.index', compact('jobs'));
    }

    /**
     * Show thank you page for applicant
     */
    public function thankYou(Applicant $applicant)
    {
        return view('frontend.job-vacancy.thank-you', compact('applicant'));
    }
}
