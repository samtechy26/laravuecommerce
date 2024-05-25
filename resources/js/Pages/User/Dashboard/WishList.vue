<script setup>
import { router, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import DashboardLayout from './DashboardLayout.vue'

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


</script>

<template>
    <DashboardLayout>
        <div class="col-span-9 border border-gray-200 p-4 rounded">
            <h4 class="text-gray-800 text-lg mb-4 font-medium uppercase">Wishlist</h4>
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
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="p-4">
                                <img v-if="product.product_images.length"
                                    :src="`../product_images/${product.product_images[0].image}`"
                                    class="w-16 md:w-32 max-w-full max-h-full" alt="Apple Watch">
                                <img v-else
                                    src="https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg"
                                    alt="image" class="w-16 md:w-32 max-w-full max-h-full object-cover object-center" />
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

        </div>
    </DashboardLayout>

</template>