<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'rating' => 'required|min:1|max:5',
            'rating_message' => 'required'
        ]);

        Rating::updateOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
        ], [
            'rating' => $request->rating,
            'rating_message' => $request->rating_message
        ]);

        // Update the average rating of the product
        $product = Product::find($request->product_id);
        $averageRating = $product->ratings()->avg('rating');
        $product->update(['average_rating' => $averageRating]);

        return back()->with('success', 'Rating submitted successfully');
    }
}
