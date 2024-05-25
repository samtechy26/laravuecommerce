<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WishList;
use App\Helper\Wish;
use App\Models\Product;
use App\Http\Resources\WishListResource;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WishListController extends Controller
{
    public function view(Request $request, Product $product) {
        $user = Auth::user();

        if($user) {
           
            $wishlist = WishList::where('user_id', $user->id)->get();
            

            if($wishlist->count() > 0) {
                return Inertia::render('User/Dashboard/WishList', [
                    'wishes' => $wishlist,
                ]);
            } else {
                return redirect()->route('products.index')->with('error', 'Wish is empty, add some items');
            }
        } else {
            $wishlist = Wish::getCookieWish();

            if(count($wishlist) > 0) {
                $wishlist = new WishListResource(Wish::getProductAndWishes());
                return Inertia::render('User/Dashboard/WishList',[
                    'WishList' => $wishlist
                ]);
            } else {
                return redirect()->route('products.index')->with('error', 'Wishlist is empty, add some items');
            }
        }
        
        
    }

    public function store(Request $request, Product $product) {
        $quantity = $request->post('quantity', 1);
        $user = $request->user();

        if($user) {
            $WishItem = WishList::where(['user_id' => $user->id, 'product_id' => $product->id])->first();

            if(!$WishItem) {
                WishList::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id
                ]);
            }
        } else {
            $wishlist = Wish::getCookieWish();
            

            $isProductExist = false;
            
            if ($wishlist) {
                foreach($wishlist as $item) {
                    if ($item['product_id'] == $product->id)
                    {
                        $isProductExist = true;
                        break;
                    }
                    
                }
                if(!$isProductExist) {
                    $wishlist[] = [
                        'user_id' => null,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $product->price
                    ];                
                }
               
            }
            else {
                $wishlist[] = [
                    'user_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price
                ];        

                
            }

            Wish::setCookieWishes($wishlist);
   
        }

        return redirect()->back()->with('success', 'Wish added successfully');
    }


    public function delete(Request $request, Product $product) {
        $user = $request->user();

        if($user) {
            WishList::query()->where(['user_id' => $user->id, 'product_id' => $product->id])->first()?->delete();

            if(WishList::count() <= 0) {
                return redirect()->route('user.home')->with('info', 'your Wish is empty');
            } else {
                return redirect()->back()->with('success', 'item removed successfully');
            }
        } else {
            $wishlist = Wish::getCookieWish();
            foreach($wishlist as $i => &$item) {
                if($item['product_id'] == $product->id) {
                    array_splice($wishlist, $i, 1);
                    break;
                }
            }
            Wish::setCookieWishes($wishlist);
            
            if(count($wishlist) <= 0) {
                return redirect()->route('user.home')->with('info', 'your Wish is empty');
            } else {
                return back()->with('success', 'item removed successfully');
            }
        }
    }
}