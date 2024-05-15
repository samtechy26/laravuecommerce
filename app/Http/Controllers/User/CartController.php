<?php

namespace App\Http\Controllers\User;

use App\Helper\Cart;
use App\Http\Controllers\Controller;
use App\Models\CartItems;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function view() {

    }

    public function store(Request $request, Product $product) {
        $quantity = $request->post('quantity', 1);
        $user = $request->user();

        if($user) {
            $cartItem = CartItems::where(['user_id' => $user->id, 'product_id' => $product->id])->first();

            if ($cartItem) {
                $cartItem->increment('quantity');
            }
            else {
                CartItems::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => 1
                ]);
            }
        } else {
            $cartItems = Cart::getCookieCartItem();

            $isProductExist = false;

            foreach($cartItems as $item) {
                if ($item['product_id'] == $product->id)
                {
                    $item['quantity'] += $quantity;
                    $isProductExist = true;
                    break;
                }
            }

            if(!$isProductExist) {
                $cartItem[] = [
                    'user_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price
                ];                
            }

            Cart::setCookieCartItems($cartItems);
        }

        return redirect()->back()->with('success', 'cart added successfully');
    }

    public function update(Request $request, Product $product) {
        $quantity = $request->integer('quantity');
        $user = $request->user();

        if($user) {
            CartItems::where(['user_id' => $user->id, 'product_id' =>$product->id])->update(['quantity' => $quantity]);
        }
        else
        {
            $cartItems = Cart::getCookieCartItem();
            foreach ($cartItems as $item) {
                if($item['product_id'] == $product->id) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }

            Cart::setCookieCartItems($cartItems);
        }

        return redirect()->back();
    }

    public function delete(Request $request, Product $product) {
        $user = $request->user();

        if($user) {
            CartItems::query()->where(['user_id' => $user->id, 'product_id' => $product->id])->first()?->delete();

            if(CartItems::count() <= 0) {
                return redirect()->route('home')->with('info', 'your cart is empty');
            } else {
                return redirect()->back()->with('success', 'item removed successfully');
            }
        } else {
            $cartItems = Cart::getCookieCartItem();
            foreach($cartItems as $i => &$item) {
                if($item['product_id'] == $product->id) {
                    array_splice($cartItems, $i, 1);
                    break;
                }
            }
            Cart::setCookieCartItems($cartItems);
            
            if(CartItems::count() <= 0) {
                return redirect()->route('home')->with('info', 'your cart is empty');
            } else {
                return redirect()->back()->with('success', 'item removed successfully');
            }
        }
    }
}
