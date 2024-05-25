<?php
namespace App\Helper;
use App\Models\Product;
use App\Models\User;
use App\Models\WishList;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Arr;

class Wish {

    public static function getCount() {
        $user = auth()->user();

        if($user) {
            return WishList::whereUserId($user->id)->count();
        } else {
            return array_reduce(self::getCookieWish(), fn ($carry) => $carry + 1, 0);
        }
    }

    public static function getWishList() {
        $user = auth()->user();

        if($user) {
            return WishList::whereUserId($user->id)->get()->map(fn (WishList $item) => [
                'product_id' => $item->product_id
            ]);
        } else {
            return self::getCookieWish();
        }
    }

    public static function getCookieWish() {
        return json_decode(request()->cookie('wish', '[]'), true);
    }

    public static function setCookieWishes (array $wishes) {
        Cookie::queue('wish', json_encode($wishes), 60*24*30);
    }

    public static function saveCookieWishes () {
        $user = auth()->user();
        $savedWishes = [];

        foreach(self::getCookieWish() as $wish)
        {
            $savedWishes[] = [
                'user_id' => $user->id,
                'product_id' => $wish['product_id']
            ];
        }

        if(!empty($savedWishes)) {
            WishList::insert($savedWishes);
        }
    }

    public static function moveWishListIntoDb() {
        $request = request();
        $wishes = self::getCookieWish();
        $newWishList = [];

        foreach($wishes as $wish) {
            $existingWish = WishList::where([
                'user_id' => $request->user()->id,
                'product_id' => $wish['product_id'],
            ]);

            if(!$existingWish) {
                $newWishList[] = [
                    'user_id' => $request->user()->id,
                    'product_id' => $wish['product_id']
                ];
            }
        }

        if(!empty($newWishList)) {
            WishList::insert($newWishList);
        }
    }

    public static function getProductAndWishes () {
        $wishes = self::getWishList();
        $ids = Arr::pluck($wishes, 'product_id');
        $products = Product::whereIn('id', $ids)->with('product_images')->get();
        $wishes = Arr::keyBy($wishes, 'product_id');

        return [$products, $wishes];
    }
}


