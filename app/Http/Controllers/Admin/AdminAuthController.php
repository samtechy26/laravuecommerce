<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm() {
        return inertia::render('Admin/Auth/login');
    }


    public function login(Request $request) {
        if(Auth::attempt((['email' => $request->email, 'password' => $request->password, 'isAdmin' => true]))) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('admin.login')->with('error', 'Invalid credentials');
    }

    public function logout(Request $request) {

        Auth::guard('web')->logout();
        $request->session()->invalidate();
    }
}
