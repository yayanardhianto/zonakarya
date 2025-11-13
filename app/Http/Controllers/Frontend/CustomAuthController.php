<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Applicant;

class CustomAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Check if user has applicant record
            $applicant = Applicant::where('user_id', $user->id)->first();
            
            // Sync email from user to applicant if applicant email is null
            if ($applicant && !$applicant->email && $user->email) {
                $applicant->update(['email' => $user->email]);
                \Log::info('Custom Auth: Synced email from user to applicant', [
                    'applicant_id' => $applicant->id,
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
            }
            
            if ($applicant) {
                // User has applicant record, redirect to status page
                return response()->json([
                    'success' => true,
                    'redirect' => route('applicant.status'),
                    'message' => 'Login successful! Redirecting to your applications...'
                ]);
            } else {
                // User doesn't have applicant record, redirect to jobs page
                return response()->json([
                    'success' => true,
                    'redirect' => route('jobs.index'),
                    'message' => 'Login successful! Redirecting to jobs...'
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'The provided credentials do not match our records.'
        ], 401);
    }
}