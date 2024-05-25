<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index () {
        $order = Order::with('order_items')->where(['created_by' => request()->user()->id])->get();
        return Inertia::render('User/Dashboard/Dashboard', ['orders' => $order]);
    }

    public function update_profile (Request $request) {
        $user = User::findOrFail(request()->user()->id);
        $user->name = $request->firstName . ' ' . $request->lastName;
        $user->email = $request->email;
        $user->update();

        $profile = Profile::where('user_id', $request->user()->id)->firstOrFail();

        $profile->birthday = $request->birthday;
        $profile->gender = $request->gender;
        $profile->phone = $request->phone;

        $profile->update();

        return back()->with('success', 'Profile updated successfully');

    }

}
