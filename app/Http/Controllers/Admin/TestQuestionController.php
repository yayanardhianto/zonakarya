<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestQuestion;
use App\Models\TestPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class TestQuestionController extends Controller
{
    public function index(Request $request)
    {
        $packageId = $request->get('package_id');
        
        $query = TestQuestion::with(['packages', 'options']);
        
        // If package_id is provided, show all questions (not just those in the package)
        // The view will handle showing which questions are in the package
        $questions = $query->orderBy('order')->paginate(10)->withQueryString();
        // $questions = $query->orderBy('order')->paginate(10);
        $packages = TestPackage::active()->get();
        
        return view('admin.test-question.index', compact('questions', 'packages', 'packageId'));
    }

    public function create(Request $request)
    {
        $packages = TestPackage::active()->get();
        $packageId = $request->get('package_id');
        
        return view('admin.test-question.create', compact('packages', 'packageId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'question_type' => 'required|in:multiple_choice,essay,scale,video_record,forced_choice',
            'points' => 'required|integer|min:1',
            'order' => 'required|integer|min:0',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*.option_text' => 'required_if:question_type,multiple_choice|string',
            'options.*.is_correct' => 'required_if:question_type,multiple_choice|boolean',
            'options.*.order' => 'required_if:question_type,multiple_choice|integer|min:0'
            // 'traits' => 'required_if:question_type,forced_choice|string'
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('question_image')) {
            $data['question_image'] = $request->file('question_image')->store('test-questions', 'public');
        }

        // Handle forced choice questions
        if ($data['question_type'] === 'forced_choice' && $request->has('traits')) {
            $traits = array_filter(array_map('trim', explode("\n", $request->traits)));
            // Store instruction in question_text and append traits as JSON
            $instruction = $data['question_text'];
            $data['question_text'] = $instruction . "\n\n<!--TRAITS_JSON:" . json_encode($traits) . "-->";
        }

        $question = TestQuestion::create($data);

        // Create options for multiple choice questions
        if ($question->isMultipleChoice() && $request->has('options')) {
            foreach ($request->options as $optionData) {
                $question->options()->create($optionData);
            }
        }

        return redirect()->route('admin.test-question.index')
            ->with('success', 'Test question created successfully.');
    }

    public function show(TestQuestion $testQuestion)
    {
        $testQuestion->load(['packages', 'options']);
        return view('admin.test-question.show', compact('testQuestion'));
    }

    public function edit(TestQuestion $testQuestion)
    {
        $packages = TestPackage::active()->get();
        $testQuestion->load('options');
        return view('admin.test-question.edit', compact('testQuestion', 'packages'));
    }

    public function update(Request $request, TestQuestion $testQuestion)
    {
        $request->validate([
            'question_text' => 'required|string',
            'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'question_type' => 'required|in:multiple_choice,essay,scale,video_record,forced_choice',
            'points' => 'required|integer|min:1',
            'order' => 'required|integer|min:0',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*.option_text' => 'required_if:question_type,multiple_choice|string',
            'options.*.is_correct' => 'required_if:question_type,multiple_choice|boolean',
            'options.*.order' => 'required_if:question_type,multiple_choice|integer|min:0'
            // 'traits' => 'required_if:question_type,forced_choice|string'
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('question_image')) {
            // Delete old image
            if ($testQuestion->question_image) {
                Storage::disk('public')->delete($testQuestion->question_image);
            }
            $data['question_image'] = $request->file('question_image')->store('test-questions', 'public');
        }

        // Handle forced choice questions
        if ($data['question_type'] === 'forced_choice' && $request->has('traits')) {
            $traits = array_filter(array_map('trim', explode("\n", $request->traits)));
            // Store instruction in question_text and append traits as JSON
            $instruction = $data['question_text'];
            $data['question_text'] = $instruction . "\n\n<!--TRAITS_JSON:" . json_encode($traits) . "-->";
        }

        $testQuestion->update($data);

        // Update options for multiple choice questions
        if ($testQuestion->isMultipleChoice()) {
            // Delete existing options
            $testQuestion->options()->delete();
            
            // Create new options
            if ($request->has('options')) {
                foreach ($request->options as $optionData) {
                    $testQuestion->options()->create($optionData);
                }
            }
        }

        return redirect()->route('admin.test-question.index')
            ->with('success', 'Test question updated successfully.');
    }

    public function destroy(TestQuestion $testQuestion)
    {
        // Delete image if exists
        if ($testQuestion->question_image) {
            Storage::disk('public')->delete($testQuestion->question_image);
        }
        
        $testQuestion->delete();

        return redirect()->route('admin.test-question.index')
            ->with('success', 'Test question deleted successfully.');
    }
}