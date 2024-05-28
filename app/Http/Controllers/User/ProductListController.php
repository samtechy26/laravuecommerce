<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductListController extends Controller
{
    public function index() {
        $products = Product::with(['brand', 'category', 'product_images', 'ratings']);
        $filteredProducts = $products->filtered()->paginate(9)->withQueryString();

        $categories = Category::with('products')->get();
        $brands = Brand::with('products')->get();
        $colors = Color::get();
        $sizes = Size::get();

        return Inertia::render('User/ProductsList', [
            'products'=> ProductResource::collection(($filteredProducts)),
            'categories' => $categories,
            'brands' => $brands,
            'colors' => $colors,
            'sizes' => $sizes
        ]);
    }

    
}
