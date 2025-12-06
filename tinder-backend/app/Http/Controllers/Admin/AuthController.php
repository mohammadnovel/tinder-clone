<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Jika sudah login, redirect sesuai role
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Check if user is blocked
            if ($user->is_blocked) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been blocked.']);
            }

            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Redirect user based on their role
     */
    protected function redirectBasedOnRole($user)
    {
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        
        // Regular user - redirect to welcome page or API info
        return redirect('/')->with('success', 'Welcome back, ' . $user->name . '! Use the mobile app or API to access your account.');
    }
}
