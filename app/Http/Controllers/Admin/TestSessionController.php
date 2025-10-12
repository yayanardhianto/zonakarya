<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestSession;
use App\Models\TestPackage;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TestSessionController extends Controller
{
    public function index(Request $request)
    {
        $query = TestSession::with(['package', 'applicant', 'jobVacancy']);
        
        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Filter by package
        if ($request->has('package_id') && $request->package_id !== '') {
            $query->where('package_id', $request->package_id);
        }
        
        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $sessions = $query->orderBy('created_at', 'desc')->paginate(15);
        $packages = TestPackage::active()->get();
        
        return view('admin.test-session.index', compact('sessions', 'packages'));
    }

    public function show(TestSession $testSession)
    {
        $testSession->load([
            'package.category',
            'applicant',
            'jobVacancy',
            'answers.question.options'
        ]);
        
        return view('admin.test-session.show', compact('testSession'));
    }

    public function destroy(TestSession $testSession)
    {
        $testSession->delete();

        return redirect()->route('admin.test-session.index')
            ->with('success', 'Test session deleted successfully.');
    }

    public function gradeEssay(Request $request, TestSession $testSession)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:test_questions,id',
            'answers.*.points_earned' => 'required|integer|min:0'
        ]);

        foreach ($request->answers as $answerData) {
            $answer = $testSession->answers()
                ->where('question_id', $answerData['question_id'])
                ->first();
                
            if ($answer) {
                $answer->update([
                    'points_earned' => $answerData['points_earned'],
                    'is_correct' => $answerData['points_earned'] > 0
                ]);
            }
        }

        // Recalculate total score
        $this->calculateSessionScore($testSession);

        return redirect()->route('admin.test-session.show', $testSession)
            ->with('success', 'Essay answers graded successfully.');
    }

    private function calculateSessionScore(TestSession $testSession)
    {
        $totalPoints = $testSession->answers()->sum('points_earned');
        $maxPoints = $testSession->package->questions()->sum('points');
        
        if ($maxPoints > 0) {
            $score = round(($totalPoints / $maxPoints) * 100);
            $isPassed = $score >= $testSession->package->passing_score;
            
            $testSession->update([
                'score' => $score,
                'is_passed' => $isPassed
            ]);
        }
    }

    public function getApplicantTestDetail($applicantId, $testType)
    {
        $applicant = \App\Models\Applicant::findOrFail($applicantId);
        
        // Tentukan package berdasarkan test type
        if ($testType === 'screening') {
            $package = \App\Models\TestPackage::where('is_screening_test', true)->first();
        } elseif ($testType === 'psychology') {
            $package = \App\Models\TestPackage::where('applicant_flow_order', 2)->first();
        } else {
            abort(404, 'Invalid test type');
        }
        
        if (!$package) {
            abort(404, 'Test package not found');
        }
        
        $testSession = TestSession::where('applicant_id', $applicantId)
            ->where('package_id', $package->id)
            ->where('status', 'completed')
            ->with(['package', 'answers.question.options'])
            ->first();
        
        if (!$testSession) {
            abort(404, 'Test session not found');
        }
        
        return view('admin.test-session.applicant-detail', compact('testSession'));
    }
}