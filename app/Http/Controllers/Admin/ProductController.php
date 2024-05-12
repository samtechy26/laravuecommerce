<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request) {
        $products = Product::all();
        return Inertia::render('Admin/Products/Index', ['products'=>$products]);
    }
}
