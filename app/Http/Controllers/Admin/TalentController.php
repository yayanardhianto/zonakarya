<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Talent;
use App\Models\Application;
use App\Models\JobVacancy;
use App\Models\Applicant;
use Illuminate\Http\Request;

class TalentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get unique talents grouped by user_id, excluding those who are onboard
        $query = Talent::with(['user', 'applicant', 'applications.jobVacancy'])
            ->selectRaw('talents.*, MAX(talents.created_at) as latest_created_at')
            ->whereNotNull('user_id')
            ->whereDoesntHave('applications', function ($q) {
                $q->where('status', 'onboard');
            })
            ->groupBy('user_id');

        // Filter by search name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Filter by potential position
        if ($request->filled('potential_position')) {
            $query->where('potential_position', 'like', '%' . $request->potential_position . '%');
        }

        $talents = $query->orderBy('latest_created_at', 'desc')->paginate(20);

        return view('admin.talents.index', compact('talents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Talent $talent)
    {
        $talent->load(['user', 'applicant', 'applications.jobVacancy']);
        
        // Get all applications for this user
        $applications = Application::with(['jobVacancy', 'testSession.package'])
            ->where('user_id', $talent->user_id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.talents.show', compact('talent', 'applications'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Talent $talent)
    {
        return view('admin.talents.edit', compact('talent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Talent $talent)
    {
        $request->validate([
            'level_potential' => 'nullable|string|max:255',
            'talent_potential' => 'nullable|string|max:255',
            'position_potential' => 'nullable|string|max:255',
            'communication' => 'nullable|integer|min:1|max:5',
            'attitude' => 'nullable|integer|min:1|max:5',
            'initiative' => 'nullable|integer|min:1|max:5',
            'leadership' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string'
        ]);

        $updateData = $request->only([
            'level_potential', 
            'talent_potential', 
            'communication', 
            'initiative', 
            'leadership', 
            'notes'
        ]);

        // Handle position_potential field mapping to potential_position
        if ($request->has('position_potential')) {
            $updateData['potential_position'] = $request->position_potential;
        }

        // Handle attitude field mapping
        if ($request->has('attitude')) {
            $updateData['attitude_level'] = $request->attitude;
        }

        // Remove null values to keep database clean
        $updateData = array_filter($updateData, function($value) {
            return $value !== null && $value !== '';
        });

        $talent->update($updateData);

        return redirect()->route('admin.talents.show', $talent)
            ->with('success', 'Talent updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Talent $talent)
    {
        $talent->delete();

        return redirect()->route('admin.talents.index')
            ->with('success', 'Talent deleted successfully.');
    }

    /**
     * Create a new application for the talent (reapply functionality)
     */
    public function reapply(Request $request, Talent $talent)
    {
        $request->validate([
            'job_vacancy_id' => 'required|exists:job_vacancies,id',
            'status' => 'required|in:pending,sent,check,short_call,group_interview,test_psychology,ojt,final_interview,sent_offering_letter,onboard',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Get the job vacancy
        $jobVacancy = JobVacancy::findOrFail($request->job_vacancy_id);

        // Get or create applicant record
        $applicant = Applicant::where('user_id', $talent->user_id)->first();
        
        if (!$applicant) {
            // Create new applicant record if it doesn't exist
            $applicant = Applicant::create([
                'user_id' => $talent->user_id,
                'name' => $talent->name,
                'email' => $talent->user->email ?? '',
                'phone' => $talent->user->phone ?? '',
                'whatsapp' => $talent->user->whatsapp ?? '',
                'status' => $request->status,
                'notes' => $request->notes
            ]);
        } else {
            // Update existing applicant status
            $applicant->update([
                'status' => $request->status,
                'notes' => $request->notes
            ]);
        }

        // Create new application
        $application = Application::create([
            'user_id' => $talent->user_id,
            'applicant_id' => $applicant->id,
            'job_vacancy_id' => $request->job_vacancy_id,
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        return redirect()->route('admin.talents.index')
            ->with('success', "Successfully created new application for {$talent->name} to {$jobVacancy->position} position.");
    }
}
