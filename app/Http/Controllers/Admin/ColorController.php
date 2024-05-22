<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;
use Inertia\Inertia;

class ColorController extends Controller
{
    public function index() {
        $colors = Color::all();
        return Inertia::render('Admin/Colors/Index', ['colors'=>$colors]);
    }

    public function store(Request $request) {
        $color = new Color();
        $color->name = $request->name;
        $color->slug = $request->name;
        $color->save();

        return back()->with('success', 'color created successfully');

    }

    public function update(Request $request, $id) {
        $color = Color::findOrFail($id);
        if($color) {
            $color->name = $request->name;
            $color->update();
            return back()->with('success', 'color updated successfully');
        }
    }

    public function destroy($id) {

       Color::findOrFail($id)->delete();

        return back()->with('success', 'color deleted successfully');
        
    }
}
