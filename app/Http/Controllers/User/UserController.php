<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Application;

class UserController extends Controller
{
    public function index () {
        $products = Product::with('brand', 'category', 'product_images')->limit(8)->get();
        return Inertia::render('User/Index', [
            'products' => $products
        ]);
    }
}
