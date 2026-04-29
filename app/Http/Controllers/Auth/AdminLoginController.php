<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function login(Request $request)
{
    $credentials = $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    // 1. Attempt the login
    if (Auth::guard('admin')->attempt($credentials)) {
        
        $user = Auth::guard('admin')->user();

        // 2. CHECK STATUS: If suspended, kick them out immediately
        if ($user->status === 'suspended') {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors(['username' => 'Your account is suspended. Please contact the Superadmin.']);
        }

        // 3. Successful Login Setup
        $request->session()->regenerate();

        // ROLE-BASED REDIRECT
        if ($user->role === 'staff') {
            return redirect()->route('members.index');
        }

        return redirect()->intended('/dashboard');
    }

    return back()->withErrors(['username' => 'Invalid credentials.']);
}

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Goes back to landing page
    }
}