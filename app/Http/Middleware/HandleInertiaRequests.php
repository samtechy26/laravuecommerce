<?php

namespace App\Http\Middleware;

use App\Helper\Cart;
use App\Helper\Wish;
use App\Http\Resources\CartResource;
use App\Http\Resources\WishListResource;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = Auth::user();
        $categories = Category::all();
        $brands = Brand::all();

        // Load the profile relationship
        if($user) {

            $user->load('profile');
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
            ],
            'cart' => new CartResource(Cart::getProductAndCartItems()),
            'gen_categories' => $categories,
            'brands' => $brands,
            'wishlist' => new WishListResource(Wish::getProductAndWishes()),
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info' => fn () => $request->session()->get('info')
            ],
            'canLogin' => app('router')->has('login'),
            'canRegister' => app('router')->has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ];
    }
}
