<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppTemplate;
use Illuminate\Http\Request;

class WhatsAppTemplateController extends Controller
{
    public function index()
    {
        $templates = WhatsAppTemplate::latest()->paginate(20);
        return view('admin.whatsapp-templates.index', compact('templates'));
    }

    public function create()
    {
        $types = [
            'short_call_invitation' => 'Short Call Invitation',
            'individual_interview_invitation' => 'Individual Interview Invitation',
            'group_interview_invitation' => 'Group Interview Invitation',
            'test_psychology_invitation' => 'Test Psychology Invitation',
            'ojt_invitation' => 'OJT Invitation',
            'final_interview_invitation' => 'Final Interview Invitation',
            'offering_letter_invitation' => 'Offering Letter Invitation',
            'test_reminder_invitation' => 'Test Reminder Invitation',
            'rejection_message' => 'Rejection Message'
        ];
        
        $defaultVariables = [
            'short_call_invitation' => ['NAME', 'POSITION', 'COMPANY', 'DATE', 'TIME'],
            'individual_interview_invitation' => ['NAME', 'POSITION', 'COMPANY', 'DATE', 'TIME', 'LOCATION'],
            'group_interview_invitation' => ['NAME', 'POSITION', 'COMPANY', 'DATE', 'TIME', 'LOCATION'],
            'test_psychology_invitation' => ['NAME', 'POSITION', 'COMPANY', 'DATE', 'TIME', 'LOCATION'],
            'ojt_invitation' => ['NAME', 'POSITION', 'COMPANY', 'DATE', 'TIME', 'LOCATION'],
            'final_interview_invitation' => ['NAME', 'POSITION', 'COMPANY', 'DATE', 'TIME', 'LOCATION'],
            'offering_letter_invitation' => ['NAME', 'POSITION', 'COMPANY', 'DATE', 'TIME', 'LOCATION'],
            'test_reminder_invitation' => ['NAME', 'POSITION', 'COMPANY', 'TEST_LINK'],
            'rejection_message' => ['NAME', 'POSITION', 'COMPANY', 'REASON']
        ];
        
        return view('admin.whatsapp-templates.create', compact('types', 'defaultVariables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:short_call_invitation,individual_interview_invitation,rejection_message,group_interview_invitation,test_psychology_invitation,ojt_invitation,final_interview_invitation,offering_letter_invitation,test_reminder_invitation',
            'template' => 'required|string',
            'variables' => 'nullable|array',
            'is_active' => 'nullable|boolean'
        ]);

        $template = WhatsAppTemplate::create([
            'name' => $request->name,
            'type' => $request->type,
            'template' => $request->template,
            'variables' => $request->variables ?? [],
            'is_active' => (bool) $request->input('is_active', false)
        ]);

        return redirect()->route('admin.whatsapp-templates.index')
            ->with('success', 'WhatsApp template created successfully.');
    }

    public function show(WhatsAppTemplate $whatsappTemplate)
    {
        return view('admin.whatsapp-templates.show', compact('whatsappTemplate'));
    }

    public function edit(WhatsAppTemplate $whatsappTemplate)
    {
        $types = [
            'short_call_invitation' => 'Short Call Invitation',
            'individual_interview_invitation' => 'Individual Interview Invitation',
            'group_interview_invitation' => 'Group Interview Invitation',
            'test_psychology_invitation' => 'Test Psychology Invitation',
            'ojt_invitation' => 'OJT Invitation',
            'final_interview_invitation' => 'Final Interview Invitation',
            'offering_letter_invitation' => 'Offering Letter Invitation',
            'test_reminder_invitation' => 'Test Reminder Invitation',
            'rejection_message' => 'Rejection Message'
        ];
        
        $defaultVariables = [
            'short_call_invitation' => ['NAME', 'POSITION', 'COMPANY', 'DATE', 'TIME'],
            'individual_interview_invitation' => ['NAME', 'POSITION', 'COMPANY', 'DATE', 'TIME', 'LOCATION'],
            'group_interview_invitation' => ['NAME', 'POSITION', 'COMPANY', 'DATE', 'TIME', 'LOCATION'],
            'test_psychology_invitation' => ['NAME', 'POSITION', 'COMPANY', 'DATE', 'TIME', 'LOCATION'],
            'ojt_invitation' => ['NAME', 'POSITION', 'COMPANY', 'DATE', 'TIME', 'LOCATION'],
            'final_interview_invitation' => ['NAME', 'POSITION', 'COMPANY', 'DATE', 'TIME', 'LOCATION'],
            'offering_letter_invitation' => ['NAME', 'POSITION', 'COMPANY', 'DATE', 'TIME', 'LOCATION'],
            'test_reminder_invitation' => ['NAME', 'POSITION', 'COMPANY', 'TEST_LINK'],
            'rejection_message' => ['NAME', 'POSITION', 'COMPANY', 'REASON']
        ];
        
        return view('admin.whatsapp-templates.edit', compact('whatsappTemplate', 'types', 'defaultVariables'));
    }

    public function update(Request $request, WhatsAppTemplate $whatsappTemplate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:short_call_invitation,individual_interview_invitation,rejection_message,group_interview_invitation,test_psychology_invitation,ojt_invitation,final_interview_invitation,offering_letter_invitation,test_reminder_invitation',
            'template' => 'required|string',
            'variables' => 'nullable|array',
            'is_active' => 'nullable|boolean'
        ]);

        $whatsappTemplate->update([
            'name' => $request->name,
            'type' => $request->type,
            'template' => $request->template,
            'variables' => $request->variables ?? [],
            'is_active' => (bool) $request->input('is_active', false)
        ]);

        return redirect()->route('admin.whatsapp-templates.index')
            ->with('success', 'WhatsApp template updated successfully.');
    }

    public function destroy(WhatsAppTemplate $whatsappTemplate)
    {
        $whatsappTemplate->delete();
        
        return redirect()->route('admin.whatsapp-templates.index')
            ->with('success', 'WhatsApp template deleted successfully.');
    }

    public function toggleStatus(WhatsAppTemplate $whatsappTemplate)
    {
        $whatsappTemplate->update(['is_active' => !$whatsappTemplate->is_active]);
        
        return response()->json([
            'success' => true,
            'message' => 'Template status updated successfully.',
            'is_active' => $whatsappTemplate->is_active
        ]);
    }

    public function getTemplates()
    {
        $templates = WhatsAppTemplate::where('is_active', true)
            ->select('id', 'name', 'type', 'template', 'variables')
            ->get();
        
        return response()->json([
            'success' => true,
            'templates' => $templates
        ]);
    }
}