<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantController extends Controller
{
    /**
     * Show user applications status page
     */
    public function status(Request $request)
    {
        // Get current user
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to view your applications.');
        }
        
        // Load all applications for this user
        $applications = Application::with(['applicant', 'jobVacancy', 'testSession.package'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();
        
        // Group applications by applicant for display
        $applicants = $applications->groupBy('applicant_id');
        
        return view('frontend.applicant.status', compact('applications', 'user'));
    }
}
