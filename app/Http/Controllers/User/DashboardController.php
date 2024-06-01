<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Profile;
use App\Models\User;
use App\Models\User_Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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

    public function view_completed_orders() {
        $orders = Order::with('order_items.product')->where(['created_by' => request()->user()->id, 'status' => 'paid'])->get();
        return Inertia::render('User/Dashboard/CompletedOrders', ['orders' => $orders]);
    }
    public function view_pending_orders() {
        $orders = Order::with('order_items.product')->where(['created_by' => request()->user()->id, 'status' => 'unpaid'])->get();
        return Inertia::render('User/Dashboard/PendingOrders', ['orders' => $orders]);
    }

    public function manage_password (Request $request) {
        $user = Auth::user();
    
    // Use Hash::check to compare the provided password with the stored hashed password
    if (Hash::check($request->currentPassword, $user->password)) {
        // Hash the new password and update it in the database
        $user->password = Hash::make($request->newPassword);
        $user->save(); // Use save() instead of update() for an existing model instance

        return back()->with('success', 'Password updated successfully.');
    } else {
        return back()->with('error', 'Incorrect current password.');
    }
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

    public function uploadProfileImage (Request $request) {
        $profile = Profile::where('user_id', request()->user()->id)->first();
        if ($request->hasFile('profile_images')) {
            $profileImages = $request->file('profile_images');

            foreach ($profileImages as $image) {
                // Generate a unique name for the image using timestamp and random string
                $uniqueName = time(). '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            }

            $image->move('profile_images', $uniqueName);

            $profile->profile_image = $uniqueName;

            $profile->update();
        }
    }

}
