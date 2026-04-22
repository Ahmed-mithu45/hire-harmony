<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Registration Logic
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'user_type' => 'required'
        ]);

        $year = date('Y');
        $prefix = ($request->user_type == 'company') ? 'COM' : 'CAN';
        $uniqueId = $prefix . "-" . $year . "-" . rand(1000, 9999);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => $request->user_type,
                'unique_id' => $uniqueId,
                'role' => 'user',
            ]);

            Auth::login($user);
            $request->session()->regenerate();

            if ($user->user_type == 'company') {
                return redirect()->route('company.dashboard')->with('success', 'Company Registered Successfully!');
            }

            return redirect('/profile')->with('success', 'Candidate Registered Successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    // Login Logic (Main Form)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // 1. Redirect ADMIN directly to dashboard
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // 2. Redirect Company
            if ($user->user_type == 'company') {
                return redirect('/company-dashboard');
            }

            // 3. Redirect Candidate
            return redirect('/profile');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    // Logout Logic
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
