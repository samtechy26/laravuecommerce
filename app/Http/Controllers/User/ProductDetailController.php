<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductDetailController extends Controller
{
    public function index($id) {
        $product = Product::where(['id' => $id])->with(['product_images', 'colors', 'sizes', 'ratings.user'])->first();
        return Inertia::render('User/ProductDetail', ['product' =>$product]);
    }
}
