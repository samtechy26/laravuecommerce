<?php

namespace App\Http\Controllers\User;

use App\Helper\Cart;
use App\Http\Controllers\Controller;
use App\Models\CartItems;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User_Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends Controller
{
    public function store(Request $request) {
        $user = Auth::user();
        $carts = $request->carts;
        $products = $request->products;

        $mergedData = [];

        // Loop through the carts array and merge with product data

        foreach($carts as $cartItem) {
            foreach($products as $product) {
                if($cartItem['product_id'] == $product['id']) {
                    //merge the cart item with the product data
                    $mergedData[] = array_merge($cartItem, ['title' => $product['title'], 'price'=>$product['price']]);
                }
            }
        }

        // stripe payment
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $lineItems = [];

        foreach($mergedData as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item['title'],
                    ],
                    'unit_amount' => (int)($item['price'] * 100),
                ],
                'quantity' => $item['quantity'],
            ];
        }
        


        $checkout_session = \Stripe\Checkout\Session::create([
        'line_items' => $lineItems,
        'mode' => 'payment',
        'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => route('cart.view'),
        ]);

        $newAddress = $request->address;

        if($newAddress['address1'] != null) {
            // $address = User_Address::where(['user_id' => $user, 'isMain'=> 1])->count();
            // if ($address > 0) {
            //     $address = User_Address::where(['user_id' => $user, 'isMain'=> 1])->update(['isMain' => 0]);
            // }

            $address = new User_Address();
            $address->address1 = $newAddress['address1'];
            $address->address2 = $newAddress['address2'];
            $address->state = $newAddress['state'];
            $address->zipcode = $newAddress['zipcode'];
            $address->city = $newAddress['city'];
            $address->country_code = $newAddress['country_code'];
            $address->type = $newAddress['type'];
            $address->user_id = Auth::user()->id;
            $address->save();
        }

        $mainAddress = User_Address::where(['user_id' => $user->id, 'isMain'=> 1])->first();

        if ($mainAddress) {
            $order = new Order();
            $order->status = 'unpaid';
            $order->user_address_id = $mainAddress->id;
            $order->total_price = $request->total;
            $order->session_id = $checkout_session->id;
            $order->created_by = $user->id;
            $order->updated_by = $user->id;

            // If a main address with isMain = 1 exist, set it's id as customer_address_id
            $order->user_address_id = $mainAddress->id;
            $order->save();

            $cartItems = CartItems::where(['user_id' => $user->id])->get();

            foreach($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->product->price
                ]);

                $cartItem->delete(); // delete cartItem after saving the order

                // remove cart items from cookies
                $cartItems = Cart::getCookieCartItem();
                foreach($cartItems as $item) {
                    unset($item);
                }

                array_splice($cartItems, 0, count($cartItems));
                Cart::setCookieCartItems($cartItems);
            }

            $paymentData = [
                'order_id' => $order->id,
                'amount' => $request->total,
                'status' => 'pending',
                'type' => 'stripe',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ];

            Payment::create($paymentData);
        }

        return Inertia::location($checkout_session->url);
    }

    public function success(Request $request) {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $sessionId = $request->get('session_id');

        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            if(!$session) {
                throw new NotFoundHttpException;
                
            }

            $order = Order::where('session_id', $session->id)->first();
            if (!$order) {
                throw new NotFoundHttpException;
            }

            if ($order->status == 'unpaid') {
                $order->status = 'paid';
                $order->save();
            }

            return redirect()->route('dashboard');

        } catch (\Exception $e){
            throw new NotFoundHttpException;
        }
    }
}
