<script setup>
import { router } from '@inertiajs/vue3';


defineProps({
    products: Array
})

const addToCart = (product) => {
    router.post(route('cart.store', product), {
        onSuccess: page => {
            if (page.props.flash.success) {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success
                })
            }
        }
    })
}
</script>

<template>
    <div v-for="product in products" :key="product.id" class="group relative">
        <div
            class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
            <img v-if="product.product_images.length" :src="`/${product.product_images[0].image}`" alt="image"
                class="h-full w-full object-cover object-center lg:h-full lg:w-full" />
            <img v-else src="https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg" alt="image"
                class="h-full w-full object-cover object-center lg:h-full lg:w-full" />

            <!-- add to cart icon -->
            <div
                class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 cursor-pointer">
                <div class="bg-blue-700 p-2 rounded-full" @click="addToCart(product)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                </div>
            </div>

            <!-- end add to cart  -->
        </div>
        <div class="mt-4 flex justify-between">
            <div>
                <h3 class="text-sm text-gray-700">
                    <span aria-hidden="true" class=" inset-0" />
                    {{ product.title }}
                </h3>
                <p class="mt-1 text-sm text-gray-500">{{ product.brand.name }}</p>
            </div>
            <p class="text-sm font-medium text-gray-900">${{ product.price }}</p>
        </div>
    </div>
</template>