<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return RedirectResponse
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return RedirectResponse
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Check if user already exists with this Google ID
            $user = User::where('google_id', $googleUser->id)->first();
            
            if ($user) {
                Auth::login($user);
                return redirect()->intended(route('home'))->with('success', 'Đăng nhập bằng Google thành công!');
            }
            
            // If no user has this google_id, check by email
            $existingUser = User::where('email', $googleUser->email)->first();
            
            if ($existingUser) {
                // Link Google account to existing user account
                $existingUser->update([
                    'google_id' => $googleUser->id,
                ]);
                
                Auth::login($existingUser);
                return redirect()->intended(route('home'))->with('success', 'Đăng nhập bằng Google thành công và đã liên kết tài khoản!');
            }
            
            // Create a new user if one doesn't exist
            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'email_verified_at' => now(),
                'google_id' => $googleUser->id,
                // Generate a random password for the user as password field is required in database
                'password' => Hash::make(Str::random(24)),
                // We can also store the Google avatar if we want, or let the user upload one
                // Socialite returns $googleUser->avatar
                'avatar' => null, // Or save the URL, but database avatar path usually expects local path
            ]);
            
            Auth::login($newUser);
            
            return redirect()->intended(route('home'))->with('success', 'Đăng ký và đăng nhập bằng Google thành công!');
            
        } catch (Exception $e) {
            logger()->error('Google Login Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('login')->withErrors([
                'email' => 'Có lỗi xảy ra khi đăng nhập bằng Google: ' . $e->getMessage()
            ]);
        }
    }
}
