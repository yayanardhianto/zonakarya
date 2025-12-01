<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Get latest application ID for applicant
Route::get('/applicant/{applicantId}/latest-application', function (Request $request, $applicantId) {
    try {
        $applicant = \App\Models\Applicant::findOrFail($applicantId);
        $latestApplication = $applicant->applications()->latest()->first();
        
        if (!$latestApplication) {
            return response()->json([
                'success' => false,
                'message' => 'No application found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'application_id' => $latestApplication->id,
            'applicant_id' => $applicant->id,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching application'
        ], 500);
    }
});
