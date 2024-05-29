<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Color;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\Size;
use App\Models\Category;
use App\Models\Product;
use App\Models\Product_Image;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request) {
        $products = Product::with(['brand', 'category', 'sizes', 'colors', 'product_images', 'ratings', 'orderItems']);
        $filteredProducts = $products->filtered()->paginate(9)->withQueryString();
        $brands = Brand::all();
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();
        return Inertia::render('Admin/Products/Index', 
        [
            'products'=> ProductResource::collection(($filteredProducts)),
            'brands'=>$brands,
            'categories'=>$categories,
            'colors' => $colors,
            'sizes' => $sizes
        ]);
    }

    public function store(Request $request) {
        $product = new Product;
        $product->title = $request->title;
        $product->price = $request->price;
        $product->weight = $request->weight;
        $product->sku = $request->title . '_' . time() ;
        $product->quantity = $request->quantity;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->save();

        //check if there are product colors
        if ($request->product_colors) {
            $productColors = $request->product_colors;
            foreach($productColors as $color) {
                ProductColor::create([
                    'color_id' => $color,
                    'product_id' => $product->id
                ]);
            }


        }

        //check product sizes
        if ($request->product_sizes) {
            $productSizes = $request->product_sizes;
            foreach($productSizes as $size) {
                ProductSize::create([
                    'size_id' => $size,
                    'product_id' => $product->id
                ]);
            }

        }

        //Check if product has images
        if($request->hasFile('product_images')) {

            $productImages = $request->file('product_images');

            foreach ($productImages as $image) {
                // Generate a unique name for the image using timestamp and random string
                $uniqueName = time(). '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                        // Store the image in the public folder with the unique name

                $image->move('product_images', $uniqueName);

                //Create a new product image record with the product_id and unique name

                Product_Image::create([
                    'product_id' => $product->id,
                    'image' =>  $uniqueName
                ]);

            }
        }

        return back()->with('success', 'product created successfully');
         
    }


    public function update(Request $request, $id) {
        $product = Product::findOrFail($id);
        $product->title = $request->title;
        $product->price = $request->price;
        $product->weight = $request->weight;
        $product->quantity = $request->quantity;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        
        //check if there are product colors
        if ($request->product_colors) {
            $validatedColor = $request->validate([
                'product_colors.*' => 'exists:colors,id', // Ensure each color ID exists in the colors table
            ]);
            $product->colors()->sync($validatedColor['product_colors']);

        }

        //check product sizes
        if ($request->product_sizes) {
            $validatedSize = $request->validate([
                'product_sizes.*' => 'exists:colors,id',
            ]);

            $product->sizes()->sync($validatedSize['product_sizes']);

        }

        //Check if product was uploaded with images
        if($request->hasFile('product_images')) {

            $productImages = $request->file('product_images');

        // Loop through the images

        foreach ($productImages as $image) {
            // Generate a unique name for the image using timestamp and random string
            $uniqueName = time(). '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
        }

        // Store the image in the public folder with the unique name

        $image->move('product_images', $uniqueName);

        //Create a new product image record with the product_id and unique name

        Product_Image::create([
            'product_id' => $product->id,
            'image' => $uniqueName
        ]);


        }

        $product->update();
        return redirect()->route('admin.products.index')->with('success', 'product updated successfully');
    }

    public function deleteImages($id) {
        $image = Product_Image::where('id', $id)->delete();

        return redirect()->route('admin.products.index')->with('success', 'Image deleted successfully');
    }


    // Delete A single Product

    public function destroy ($id) {
        $product = Product::findOrFail($id)->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');

    }


}
