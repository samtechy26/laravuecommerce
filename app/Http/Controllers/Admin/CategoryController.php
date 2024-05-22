<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    public function index () {
        $categories = Category::all();
        return Inertia::render('Admin/Categories/Index', ['categories' => $categories]);
    }

    public function store(Request $request) {
        $category = new Category();
        $category->name = $request->name;
        $category->slug = $request->slug;

        if ($request->hasFile('category_images')) {
            $featureImages = $request->file('category_images');

            foreach ($featureImages as $image) {
                // Generate a unique name for the image using timestamp and random string
                $uniqueName = time(). '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            }

            $image->move('category_images', $uniqueName);

            $category->feature_image = $uniqueName;

            $category->save();

            return back()->with('success', 'category created successfully');
        }

    
        
    }

    public function update(Request $request, $id) {
        $category = Category::findOrFail($id);

        $category->name = $request->name;
        $category->slug = $request->slug;

        if ($request->hasFile('category_images')) {
            $featureImages = $request->file('category_images');
            foreach ($featureImages as $image) {
                // Generate a unique name for the image using timestamp and random string
                $uniqueName = time(). '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            }
            $image->move('category_images', $uniqueName);
            $category->feature_image = $uniqueName;
            
        }

        $category->update();

        return back()->with('success', 'category updated successfully');

    }

    public function destroy ($id) {
        Category::findOrFail($id)?->delete();

        return back()->with('success', 'category deleted successfully');
    }
}
