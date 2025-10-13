<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JobVacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = JobVacancy::with('locationBranch')->latest()->paginate(10);
        return view('admin.job-vacancy.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $locations = Location::active()->get();
        return view('admin.job-vacancy.create', compact('locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'position' => 'required|string|max:255',
            'location_id' => 'required|exists:locations,id',
            'work_type' => 'required|in:Full-Time,Part-Time,Contract,Freelance,Internship',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'education' => 'required|in:SMA,D3,S1,S2,S3,Tidak Ada Persyaratan',
            'gender' => 'required|in:Pria,Wanita,Semua Jenis',
            'age_min' => 'nullable|integer|min:18|max:65',
            'age_max' => 'nullable|integer|min:18|max:65|gte:age_min',
            'experience_years' => 'required|integer|min:0',
            'description' => 'required|string',
            'responsibilities' => 'nullable|string',
            'benefits' => 'nullable|string',
            'application_deadline' => 'nullable|date|after:today',
            'status' => 'required|in:active,inactive,closed',
            'specific_requirements' => 'nullable|array',
            'specific_requirements.*' => 'string|max:255',
            'show_salary' => 'boolean',
            'show_age' => 'boolean'
        ]);

        $data = $request->all();

        // Set default company information
        $data['company_name'] = 'Zona Karya Nusantara';
        $data['contact_email'] = 'hr@zona-karya.id';
        $data['contact_phone'] = '+62-21-1234-5678';
        $data['location'] = Location::find($request->location_id)->full_name;
        
        // Ensure boolean values are properly set
        $data['show_salary'] = $request->has('show_salary') ? (bool) $request->show_salary : false;
        $data['show_age'] = $request->has('show_age') ? (bool) $request->show_age : false;

        // Generate unique code
        $data['unique_code'] = JobVacancy::generateUniqueCode();

        JobVacancy::create($data);

        return redirect()->route('admin.job-vacancy.index')
            ->with('success', 'Lowongan pekerjaan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JobVacancy $jobVacancy)
    {
        $jobVacancy->load('locationBranch');
        return view('admin.job-vacancy.show', compact('jobVacancy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobVacancy $jobVacancy)
    {
        $locations = Location::active()->get();
        $jobVacancy->load('locationBranch');
        return view('admin.job-vacancy.edit', compact('jobVacancy', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobVacancy $jobVacancy)
    {
        $request->validate([
            'position' => 'required|string|max:255',
            'location_id' => 'required|exists:locations,id',
            'work_type' => 'required|in:Full-Time,Part-Time,Contract,Freelance,Internship',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'education' => 'required|in:SMA,D3,S1,S2,S3,Tidak Ada Persyaratan',
            'gender' => 'required|in:Pria,Wanita,Semua Jenis',
            'age_min' => 'nullable|integer|min:18|max:65',
            'age_max' => 'nullable|integer|min:18|max:65|gte:age_min',
            'experience_years' => 'required|integer|min:0',
            'description' => 'required|string',
            'responsibilities' => 'nullable|string',
            'benefits' => 'nullable|string',
            'application_deadline' => 'nullable|date|after:today',
            'status' => 'required|in:active,inactive,closed',
            'specific_requirements' => 'nullable|array',
            'specific_requirements.*' => 'string|max:255',
            'show_salary' => 'boolean',
            'show_age' => 'boolean'
        ]);

        $data = $request->all();

        // Update location string
        $data['location'] = Location::find($request->location_id)->full_name;
        
        // Ensure boolean values are properly set
        $data['show_salary'] = $request->has('show_salary') ? (bool) $request->show_salary : false;
        $data['show_age'] = $request->has('show_age') ? (bool) $request->show_age : false;

        $jobVacancy->update($data);

        return redirect()->route('admin.job-vacancy.index')
            ->with('success', 'Lowongan pekerjaan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobVacancy $jobVacancy)
    {
        $jobVacancy->delete();

        return redirect()->route('admin.job-vacancy.index')
            ->with('success', 'Lowongan pekerjaan berhasil dihapus.');
    }

    /**
     * Toggle status of the job vacancy
     */
    public function toggleStatus(JobVacancy $jobVacancy)
    {
        $jobVacancy->update([
            'status' => $jobVacancy->status === 'active' ? 'inactive' : 'active'
        ]);

        return redirect()->back()
            ->with('success', 'Status lowongan pekerjaan berhasil diubah.');
    }
}