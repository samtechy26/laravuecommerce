<script setup>
import { Link } from '@inertiajs/vue3'
import { router, useForm } from '@inertiajs/vue3';

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

// add to wishlist 

function addToWishList(product) {
    router.post(route('wishlist.store', { product: product }), {
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
defineProps({
    top_products: Array
})
</script>

<template>

    <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
        <!-- new arrival -->

        <h2 class="text-2xl font-medium text-gray-800 uppercase mb-6">top new arrival</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div v-for="product in top_products" :key="product.id"
                class="bg-white shadow rounded overflow-hidden group">
                <div class="relative">
                    <img :src="`../product_images/${product.product_images[0].image}`" alt="product 1" class="w-full">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center 
                        justify-center gap-2 opacity-0 group-hover:opacity-100 transition">
                        <Link :href="route('product.single', { id: product.id })"
                            class="text-white text-lg w-9 h-8 rounded-full bg-primary flex items-center justify-center hover:bg-gray-800 transition"
                            title="view product">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        </Link>
                        <a @click="addToWishList(product)"
                            class=" cursor-pointer text-white text-lg w-9 h-8 rounded-full bg-primary flex items-center justify-center hover:bg-gray-800 transition"
                            title="add to wishlist">
                            <i class="fa-solid fa-heart"></i>
                        </a>
                    </div>
                </div>
                <div class="pt-4 pb-3 px-4">
                    <Link :href="route('product.single', { id: product.id })">
                    <h4 class="uppercase font-medium text-xl mb-2 text-gray-800 hover:text-primary transition">
                        {{ product.title }}</h4>
                    </Link>
                    <div class="flex items-baseline mb-1 space-x-2">
                        <p class="text-xl text-primary font-semibold">${{ product.price }}</p>

                    </div>
                    <div class="flex items-center">
                        <div class="flex gap-1 text-sm ">
                            <span v-for="item in 5" :key="item"><i class="fa-solid fa-star"
                                    :class="`${item <= product.average_rating ? 'text-yellow-400' : 'text-gray-400'}`"></i>
                            </span>

                        </div>
                        <div class="text-xs text-gray-500 ml-3">({{ product.ratings?.length }})</div>
                    </div>
                </div>
                <a @click="addToCart(product)"
                    class="block w-full py-1 text-center cursor-pointer text-white bg-primary border border-primary rounded-b hover:bg-transparent hover:text-primary transition">Add
                    to cart</a>
            </div>
        </div>

        <!-- ./new arrival -->
    </div>

</template>