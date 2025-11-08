<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        // Only force account selection if user is not logged in (guest)
        // If already logged in, allow automatic login with existing account
        $socialiteDriver = Socialite::driver('google');
        if (!Auth::check()) {
            // User is not logged in, force account selection
            $socialiteDriver = $socialiteDriver->with(['prompt' => 'select_account']);
        }
        
        return $socialiteDriver->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = $this->findOrCreateUser($googleUser, 'google');
            
            Auth::login($user, true);
            
            return redirect()->intended('/dashboard');
            
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Google login failed. Please try again.');
        }
    }

    /**
     * Redirect to LinkedIn OAuth
     */
    public function redirectToLinkedIn()
    {
        return Socialite::driver('linkedin')->redirect();
    }

    /**
     * Handle LinkedIn OAuth callback
     */
    public function handleLinkedInCallback()
    {
        try {
            $linkedinUser = Socialite::driver('linkedin')->user();
            
            $user = $this->findOrCreateUser($linkedinUser, 'linkedin');
            
            Auth::login($user, true);
            
            return redirect()->intended('/dashboard');
            
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'LinkedIn login failed. Please try again.');
        }
    }

    /**
     * Find or create user from social provider
     */
    private function findOrCreateUser($socialUser, $provider)
    {
        // Check if user exists by provider_id
        $user = User::where('provider', $provider)
                   ->where('provider_id', $socialUser->getId())
                   ->first();

        if ($user) {
            return $user;
        }

        // Check if user exists by email
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Update existing user with social provider info
            $user->update([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'email_verified_at' => now(),
            ]);

            return $user;
        }

        // Create new user
        $user = User::create([
            'name' => $socialUser->getName(),
            'email' => $socialUser->getEmail(),
            'password' => Hash::make(Str::random(16)), // Random password for social users
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'avatar' => $socialUser->getAvatar(),
            'email_verified_at' => now(),
            'status' => 'active',
            'is_banned' => false,
        ]);

        return $user;
    }
}