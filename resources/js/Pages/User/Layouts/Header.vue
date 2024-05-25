<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { ElMessageBox } from 'element-plus'

const canLogin = usePage().props.canLogin;
const canRegister = usePage().props.canRegister;
const auth = usePage().props.auth;
const cart = computed(() => usePage().props.cart)
const wish = computed(() => usePage().props.wishlist)

const dialogVisible = ref(false)

const products = computed(() => usePage().props.wishlist.data.product)

const remove = (product) => {
    router.delete(route('wishlist.delete', product),
        {
            preserveState: true,
            preserveScroll: true,
            replace: true
        }

    );
};

function addToCart(product) {
    router.post(route('cart.store', { product: product }), {
        onSuccess: (page) => {
            Swal.fire({
                toast: true,
                icon: 'success',
                position: 'top-end',
                showConfirmButton: false,
                title: page.props.flash.success
            })
        }
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}


</script>

<template>
    <el-dialog v-model="dialogVisible" title="Wishlist" width="500" :before-close="handleClose">

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-16 py-3">
                            <span class="sr-only">Image</span>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Product
                        </th>

                        <th scope="col" class="px-6 py-3">
                            Price
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in products" :key="product.id"
                        class="overflow-hidden group bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="p-4 ">
                            <div class="relative">
                                <img v-if="product.product_images.length"
                                    :src="`../product_images/${product.product_images[0].image}`"
                                    class="w-16 md:w-32 max-w-full max-h-full " alt="Apple Watch">
                                <img v-else
                                    src="https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg"
                                    alt="image" class="w-16 md:w-32 max-w-full max-h-full object-cover object-center" />
                                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center 
                                justify-center gap-2 opacity-0 group-hover:opacity-100 transition">
                                    <Link :href="route('product.single', { id: product.id })"
                                        class="text-white text-lg w-9 h-8 rounded-full bg-primary flex items-center justify-center hover:bg-gray-800 transition"
                                        title="view product">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    </Link>
                                    <a @click="addToCart(product)"
                                        class=" cursor-pointer text-white text-lg w-9 h-8 rounded-full bg-primary flex items-center justify-center hover:bg-gray-800 transition"
                                        title="add to cart">
                                        <i class="fa-solid fa-bag-shopping"></i>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                            {{ product.title }}
                        </td>

                        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                            ${{ product.price }}
                        </td>
                        <td class="px-6 py-4">
                            <a @click="remove(product)"
                                class="cursor-pointer font-medium text-red-600 dark:text-red-500 hover:underline">Remove</a>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </el-dialog>
    <!-- header -->
    <header class="py-4 shadow-sm bg-white">
        <div class="container flex items-center justify-between">
            <Link :href="route('user.home')" class="flex ms-2 md:me-24">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                <path fill-rule="evenodd"
                    d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 0 0 4.25 22.5h15.5a1.875 1.875 0 0 0 1.865-2.071l-1.263-12a1.875 1.875 0 0 0-1.865-1.679H16.5V6a4.5 4.5 0 1 0-9 0ZM12 3a3 3 0 0 0-3 3v.75h6V6a3 3 0 0 0-3-3Zm-3 8.25a3 3 0 1 0 6 0v-.75a.75.75 0 0 1 1.5 0v.75a4.5 4.5 0 1 1-9 0v-.75a.75.75 0 0 1 1.5 0v.75Z"
                    clip-rule="evenodd" />
            </svg>LV.SHOP

            </Link>

            <div class="w-full max-w-xl relative flex">
                <span class="absolute left-4 top-3 text-lg text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" id="search"
                    class="w-full border border-primary border-r-0 pl-12 py-3 pr-3 rounded-l-md focus:outline-none hidden md:flex"
                    placeholder="search">
                <button
                    class="bg-primary border border-primary text-white px-8 rounded-r-md hover:bg-transparent hover:text-primary transition hidden md:flex">Search</button>
            </div>

            <div class="flex items-center space-x-4">
                <a @click="dialogVisible = true"
                    class=" cursor-pointer text-center text-gray-700 hover:text-primary transition relative">
                    <div class="text-2xl">
                        <i class="fa-regular fa-heart"></i>
                    </div>
                    <div class="text-xs leading-3">Wishlist</div>
                    <div
                        class="absolute right-0 -top-1 w-5 h-5 rounded-full flex items-center justify-center bg-primary text-white text-xs">
                        {{ wish.data.count }}</div>
                </a>
                <Link :href="route('cart.view')"
                    class="text-center text-gray-700 hover:text-primary transition relative">
                <div class="text-2xl">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <div class="text-xs leading-3">Cart</div>
                <div
                    class="absolute -right-3 -top-1 w-5 h-5 rounded-full flex items-center justify-center bg-primary text-white text-xs">
                    {{ cart.data.count }}</div>
                </Link>
                <a href="#" class="text-center text-gray-700 hover:text-primary transition relative">
                    <div class="text-2xl">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div class="text-xs leading-3">Account</div>
                </a>
            </div>
        </div>
    </header>
    <!-- ./header -->


    <!-- navbar -->
    <nav class="bg-gray-800">
        <div class="container flex">
            <div class="px-8 py-4 bg-primary md:flex items-center cursor-pointer relative group hidden">
                <span class="text-white">
                    <i class="fa-solid fa-bars"></i>
                </span>
                <span class="capitalize ml-2 text-white hidden">All Categories</span>

                <!-- dropdown -->
                <div
                    class="absolute w-full left-0 top-full bg-white shadow-md py-3 divide-y divide-gray-300 divide-dashed opacity-0 group-hover:opacity-100 transition duration-300 invisible group-hover:visible">
                    <a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 transition">
                        <img src="/images/icons/sofa.svg" alt="sofa" class="w-5 h-5 object-contain">
                        <span class="ml-6 text-gray-600 text-sm">Sofa</span>
                    </a>
                    <a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 transition">
                        <img src="/images/icons/terrace.svg" alt="terrace" class="w-5 h-5 object-contain">
                        <span class="ml-6 text-gray-600 text-sm">Terarce</span>
                    </a>
                    <a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 transition">
                        <img src="/images/icons/bed.svg" alt="bed" class="w-5 h-5 object-contain">
                        <span class="ml-6 text-gray-600 text-sm">Bed</span>
                    </a>
                    <a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 transition">
                        <img src="/images/icons/office.svg" alt="office" class="w-5 h-5 object-contain">
                        <span class="ml-6 text-gray-600 text-sm">office</span>
                    </a>
                    <a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 transition">
                        <img src="/images/icons/outdoor-cafe.svg" alt="outdoor" class="w-5 h-5 object-contain">
                        <span class="ml-6 text-gray-600 text-sm">Outdoor</span>
                    </a>
                    <a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 transition">
                        <img src="/images/icons/bed-2.svg" alt="Mattress" class="w-5 h-5 object-contain">
                        <span class="ml-6 text-gray-600 text-sm">Mattress</span>
                    </a>
                </div>
            </div>

            <div class="flex items-center justify-between flex-grow md:pl-12 py-5">
                <div class="flex items-center space-x-6 capitalize">
                    <Link :href="route('user.home')" class="text-gray-200 hover:text-white transition">Home</Link>
                    <Link :href="route('products.index')" class="text-gray-200 hover:text-white transition">Shop</Link>
                    <a href="#" class="text-gray-200 hover:text-white transition">About us</a>
                    <Link :href="route('contact')" class="text-gray-200 hover:text-white transition">Contact us</Link>
                </div>
                <Link :href="route('login')" class="text-gray-200 hover:text-white transition">Login / Register</Link>
            </div>
        </div>
    </nav>
    <!-- ./navbar -->
</template>
