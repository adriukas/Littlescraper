<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthController extends Controller
{
    public function loginCheck(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            session(['is_logged_in' => true, 'user_email' => $request->email]);
            Log::info("[Auth] User logged in: " . $request->email);
            return redirect()->route('home');
        }

        Log::warning("[Auth] Failed login attempt for: " . $request->email);
        return back()
            ->withErrors(['login' => 'Incorrect email or password.'])
            ->withInput($request->only('email'));
    }

    public function logout()
    {
        Auth::logout();
        session()->forget(['is_logged_in', 'user_email']);
        Log::info("[Auth] User logged out.");
        return redirect()->route('info');
    }
}
