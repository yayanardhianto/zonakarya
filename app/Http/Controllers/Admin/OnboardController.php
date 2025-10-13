<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobVacancy;
use App\Models\Talent;
use Illuminate\Http\Request;

class OnboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['applicant', 'jobVacancy', 'user'])
            ->where('status', 'onboard')
            ->orderBy('created_at', 'desc');

        // Filter by job vacancy
        if ($request->filled('job_vacancy_id')) {
            $query->where('job_vacancy_id', $request->job_vacancy_id);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('applicant', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $applications = $query->paginate(20);

        // Get job vacancies for filter
        $jobVacancies = JobVacancy::orderBy('position')->get();

        // Get statistics
        $totalOnboard = Application::where('status', 'onboard')->count();
        $thisMonthOnboard = Application::where('status', 'onboard')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('admin.onboard.index', compact(
            'applications',
            'jobVacancies',
            'totalOnboard',
            'thisMonthOnboard'
        ));
    }

    /**
     * Get talent by user_id for onboard applicants
     */
    public function getTalentByUserId($userId)
    {
        $talent = Talent::where('user_id', $userId)->first();
        return $talent;
    }
}
