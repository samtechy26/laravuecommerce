<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function completed () {
        $orders = Order::with(['order_items.product', 'user'])->where([ 'status' => 'paid'])->get();
        return Inertia::render('Admin/Orders/Completed', ['orders' => $orders]);
    }

    public function pending () {
        $orders = Order::with(['order_items.product', 'user'])->where([ 'status' => 'unpaid'])->get();
        return Inertia::render('Admin/Orders/Pending', ['orders' => $orders]);
    }
}
