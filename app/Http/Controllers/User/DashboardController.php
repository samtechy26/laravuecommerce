<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index () {
        $order = Order::with('order_items')->get();
        return Inertia::render('User/Dashboard', ['orders' => $order]);
    }
}
