<script setup>

import { Link } from '@inertiajs/vue3';

defineProps({
    categories: Array
})

</script>

<template>
    <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
        <!-- categories -->

        <h2 class="text-2xl font-medium text-gray-800 uppercase mb-6">shop by category</h2>
        <div class="grid grid-cols-3 gap-3">
            <div v-for="cat in categories" :key="cat.id" class="relative rounded-sm overflow-hidden group">
                <img :src="`../category_images/${cat.feature_image}`" :alt="`category ${cat.id}`" class="w-full">
                <Link :href="`/products?categories[]=${cat.id}`"
                    class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center text-xl text-white font-roboto font-medium group-hover:bg-opacity-60 transition">
                {{
                    cat.name }}</Link>
            </div>

        </div>

        <!-- ./categories -->
    </div>

</template>