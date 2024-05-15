<?php
namespace App\Helper;
use App\Models\CartItems;
use App\Models\Product;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Arr;


class Cart {

    public static function getCount() {
        if ($user = auth()->user()) {
            return CartItems:: whereUserId($user->id)->sum('quantity');
        }
    }

    public static function getCartItems() {
        if ($user = auth()->user()) {
            return CartItems::whereUserId($user->id)->get()->map(fn (CartItems $item) => ['product_id' => $item->product_id, 'quantity' => $item->quantity]);
        }
        
    }

    public static function getCookieCartItem() {
        return json_decode(request()->cookie('cart_items', '[]'));
    }

    public static function setCookieCartItems ($item) {
        Cookie::queue('cart_item', fn(int $carry, array $item)=> $carry + $item['quantity'], 0);
    }

    public static function saveCookieCartItems () {
        $user = auth()->user();
        $userCartItems = CartItems::where(['user_id' => $user->id])->get()->keyBy('product_id');
        $savedCartItems = [];

        foreach (self::getCookieCartItem() as $cartItem) {
            if(isset($userCartitems[$cartItem['product_id']])) {
                $userCartItems[$cartItem['product_id']]->update(['quantity' => $cartItem['quantity']]);
                continue;
            }

            $savedCartItems[] = [
                'user_id' => $user->id,
                'product_id' => $cartItem['product_id'],
                'quantity' => $cartItem['quantity'],
            ];
        }

        if (!empty($savedCartItems)) {
            CartItems::insert($savedCartItems);
        }
    }

    public static function moveCartItemsIntoDb() {
        $request = request();
        $cartItems = self::getCookieCartItem();
        $newCartItems = [];

        foreach($cartItems as $cartItem) {
            // Check if the record already exist in the database 
            $existingCartItem = CartItems::where([
                'user_id' => $request->user()->id,
                'product_id' => $cartItem['product_id'],
                'quantity' => $cartItem['quantity']
            ]);
        }

        if(!empty($newCartItems)) {
            // Insert the new cart items into the database
            CartItems::insert($newCartItems);
        }
    }

    public static function getProductAndCartItems() {

        $user = auth()->user();
        if($user) {
            $cartItems = self::getCartItems();
            $ids = Arr::pluck($cartItems, 'product_id');
            $products = Product::whereIn('id', $ids)->with('product_images')->get();
            $cartItems = Arr::keyBy($cartItems, 'product_id');
            return [$products, $cartItems];
        } else {
            $cartItems = self::getCookieCartItem();
            $ids = Arr::pluck($cartItems, 'product_id');
            $products = Product::whereIn('id', $ids)->with('product_images')->get();
            $cartItems = Arr::keyBy($cartItems, 'product_id');
            return [$products, $cartItems];
        }

    }
}