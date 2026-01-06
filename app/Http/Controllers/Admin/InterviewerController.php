<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interviewer;
use Illuminate\Http\Request;

class InterviewerController extends Controller
{
    public function index()
    {
        $interviewers = Interviewer::orderBy('name')->paginate(20);
        return view('admin.interviewers.index', compact('interviewers'));
    }

    public function create()
    {
        return view('admin.interviewers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:interviewers,name'
        ]);

        try {
            $interviewer = Interviewer::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone
            ]);

            // Check if this is an AJAX request
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Interviewer created successfully',
                    'interviewer' => $interviewer
                ]);
            }

            return redirect()->route('admin.interviewers.index')
                ->with('success', 'Interviewer created successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating interviewer: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating interviewer: ' . $e->getMessage());
        }
    }

    public function show(Interviewer $interviewer)
    {
        return view('admin.interviewers.show', compact('interviewer'));
    }

    public function edit(Interviewer $interviewer)
    {
        return view('admin.interviewers.edit', compact('interviewer'));
    }

    public function update(Request $request, Interviewer $interviewer)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:interviewers,name,' . $interviewer->id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20'
        ]);

        try {
            $interviewer->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone
            ]);

            return redirect()->route('admin.interviewers.index')
                ->with('success', 'Interviewer updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating interviewer: ' . $e->getMessage());
        }
    }

    public function destroy(Interviewer $interviewer)
    {
        try {
            // Check if interviewer is being used in any applications
            if ($interviewer->applications()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete interviewer because they are assigned to applications. Please reassign the applications first.');
            }

            $interviewer->delete();

            return redirect()->route('admin.interviewers.index')
                ->with('success', 'Interviewer deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting interviewer: ' . $e->getMessage());
        }
    }
}
