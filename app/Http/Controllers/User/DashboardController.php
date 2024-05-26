<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Profile;
use App\Models\User;
use App\Models\User_Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index () {
        $order = Order::with('order_items')->where(['created_by' => request()->user()->id])->get();
        return Inertia::render('User/Dashboard/Dashboard', ['orders' => $order]);
    }

    public function view_address() {
        $user = Auth::user();
        $userAddress = User_Address::where(['user_id' => $user->id, 'isMain' => 1])->first();
        
        return Inertia::render('User/Dashboard/Address', ['address' => $userAddress]);
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

    public function update_address(Request $request) {
        $user = Auth::user();
        $userAddress = User_Address::where(['user_id' => $user->id, 'isMain' => 1])->first();

        $userAddress->address1 = $request->address1;
        $userAddress->address2 = $request->address2;
        $userAddress->country_code = $request->country_code;
        $userAddress->state = $request->state;
        $userAddress->city = $request->city;
        $userAddress->zipcode = $request->zipcode;
        $userAddress->type = $request->type;

        $userAddress->update();

        return back()->with('success', 'address updated successfully');
        
    }

}
