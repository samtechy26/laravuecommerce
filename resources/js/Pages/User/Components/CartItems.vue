<script setup>
import { usePage, router } from '@inertiajs/vue3';
import { computed } from 'vue';



const carts = computed(() => usePage().props.cart.data.items);

const products = computed(() => usePage().props.cart.data.product);

const itemId = (id) => carts.value.findIndex(item => item.product_id === id);

const update = (product, quantity) => {
    router.patch(route('cart.update', product), {
        quantity,
    },);
};

const remove = (product) => {
    router.delete(route('cart.delete', product),

    );
};
</script>

<template>
    <tr v-for="product in products" :key="product.id"
        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
        <td class="p-4">
            <img v-if="product.product_images.length" :src="`../product_images/${product.product_images[0].image}`"
                class="w-16 md:w-32 max-w-full max-h-full" alt="Apple Watch">
            <img v-else src="https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg" alt="image"
                class="w-16 md:w-32 max-w-full max-h-full object-cover object-center" />
        </td>
        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
            {{ product.title }}
        </td>
        <td class="px-6 py-4">
            <div class="flex items-center">
                <button @click.prevent="update(product, carts[itemId(product.id)].quantity - 1)"
                    :disabled="carts[itemId(product.id)].quantity <= 1"
                    :class="[carts[itemId(product.id)].quantity > 1 ? 'cursor-pointer text-blue-600' : 'cursor-not-allowed text-gray-300 dark:text-gray-500', 'inline-flex items-center justify-center p-1 me-3 text-sm font-medium h-6 w-6 text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700']"
                    type="button">
                    <span class="sr-only">Quantity button</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 18 2">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 1h16" />
                    </svg>
                </button>
                <div>
                    <input type="number" :id="product.id" v-model="carts[itemId(product.id)].quantity"
                        class="bg-gray-50 w-14 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-2.5 py-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="1" required />
                </div>
                <button @click.prevent="update(product, carts[itemId(product.id)].quantity + 1)"
                    class="inline-flex items-center justify-center h-6 w-6 p-1 ms-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                    type="button">
                    <span class="sr-only">Quantity button</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 18 18">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 1v16M1 9h16" />
                    </svg>
                </button>
            </div>
        </td>
        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
            ${{ product.price }}
        </td>
        <td class="px-6 py-4">
            <a @click="remove(product)"
                class="cursor-pointer font-medium text-red-600 dark:text-red-500 hover:underline">Remove</a>
        </td>
    </tr>
</template>