<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BrandController extends Controller
{
    public function index() {
        $brands = Brand::all();
        return Inertia::render('Admin/Brands/Index', ['brands'=>$brands]);
    }

    public function store(Request $request) {
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = $request->name;
        $brand->save();

        return back()->with('success', 'brand created successfully');

    }

    public function update(Request $request, $id) {
        $brand = Brand::findOrFail($id);
        if($brand) {
            $brand->name = $request->name;
            $brand->update();
            return back()->with('success', 'brand updated successfully');
        }
    }

    public function destroy($id) {

       Brand::findOrFail($id)->delete();

        return back()->with('success', 'brand deleted successfully');
        
    }
}
