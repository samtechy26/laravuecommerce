<?php

namespace App\Http\Controllers\User;

use App\Helper\Cart;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\CartItems;
use App\Models\Product;
use App\Models\User_Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CartController extends Controller
{
    public function view(Request $request, Product $product) {
        $user = Auth::user();

        if($user) {
           
            $cartItems = CartItems::where('user_id', $user->id)->get();
            
            
            $userAddress = User_Address::where(['user_id' => $user->id, 'isMain' => 1])->first();

            if($cartItems->count() > 0) {
                return Inertia::render('User/CartList', [
                    'cartItems' => $cartItems,
                    'userAddress' => $userAddress
                ]);
            } else {
                return redirect()->route('products.index')->with('error', 'cart is empty, add some items');
            }
        } else {
            $cartItems = Cart::getCookieCartItem();

            if(count($cartItems) > 0) {
                $cartItems = new CartResource(Cart::getProductAndCartItems());
                return Inertia::render('User/CartList',[
                    'cartItems' => $cartItems
                ]);
            } else {
                return redirect()->route('products.index')->with('error', 'cart is empty, add some items');
            }
        }
        
        
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
            
            if ($cartItems) {
                foreach($cartItems as $item) {
                    if ($item['product_id'] === $product->id)
                    {
                        $item['quantity'] = $quantity;
                        $isProductExist = true;
                        break;
                    }
                    

                }
                if(!$isProductExist) {
                    $cartItems[] = [
                        'user_id' => null,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $product->price
                    ];                
                }
               
            }
            else {
                $cartItems[] = [
                    'user_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price
                ];        

                
            }

            Cart::setCookieCartItems($cartItems);
   
        }

        return back()->with('success', 'cart added successfully');
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
            if($cartItems) {
                foreach ($cartItems as &$item) {
                    if($item['product_id'] == $product->id) {
                        $item['quantity'] = $quantity;
                        break;
                    }
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
                return redirect()->route('user.home')->with('info', 'your cart is empty');
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
            
            if(count($cartItems) <= 0) {
                return redirect()->route('user.home')->with('info', 'your cart is empty');
            } else {
                return back()->with('success', 'item removed successfully');
            }
        }
    }
}
