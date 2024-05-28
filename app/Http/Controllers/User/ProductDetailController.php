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
        $related_products = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id) // Exclude the product itself
        ->with(['product_images', 'ratings'])
        ->limit(4)
        ->get();
        return Inertia::render('User/ProductDetail', ['product' =>$product, 'related_products' => $related_products]);
    }
}
