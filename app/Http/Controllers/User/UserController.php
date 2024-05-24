<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Application;

class UserController extends Controller
{
    public function index () {
        $products = Product::with('brand', 'category', 'product_images')->limit(8)->get();
        $top_products = Product::orderBy('created_at')->with('product_images')->limit(4)->get();
        $categories = Category::orderBy('created_at')->limit(6)->get();
        return Inertia::render('User/Index', [
            'products' => $products,
            'top_products' => $top_products,
            'categories' => $categories
        ]);
    }

    public function contact() {
        return Inertia::render('User/Contact');
    }
}
