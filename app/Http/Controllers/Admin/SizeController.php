<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Size;
use Inertia\Inertia;

class SizeController extends Controller
{
    public function index() {
        $sizes = Size::all();
        return Inertia::render('Admin/Sizes/Index', ['sizes'=>$sizes]);
    }

    public function store(Request $request) {
        $size = new Size();
        $size->name = $request->name;
        $size->slug = $request->name;
        $size->save();

        return back()->with('success', 'size created successfully');

    }

    public function update(Request $request, $id) {
        $size = Size::findOrFail($id);
        if($size) {
            $size->name = $request->name;
            $size->update();
            return back()->with('success', 'size updated successfully');
        }
    }

    public function destroy($id) {

        Size::findOrFail($id)->delete();

        return back()->with('success', 'size deleted successfully');
        
    }
}
