<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ManageUsersController extends Controller
{
    public function index () {
        $users = Profile::with('user')->whereHas('user', function ($query) {
            $query->where('isAdmin', 0);
        })->get();
        return Inertia::render('Admin/Users/Index', ['users' => $users]);
    }

    public function update (Request $request) {
        $user = User::where('id', $request->userId)->first();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->update();

        $profile = Profile::where('user_id', $request->userId)->firstOrFail();

        $profile->birthday = $request->birthday;
        $profile->gender = $request->gender;
        $profile->phone = $request->phone;

        $profile->update();

        return back()->with('success', 'User updated successfully');
    }

    public function deactivate (Request $request) {
        $user = User::where('id', $request->userId)->first();
        $user->status = 0;
        $user->update();

        return back()->with('success', 'user deactivated');
    }
}
