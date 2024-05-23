<script setup>

defineProps({
    categories: Array
})

</script>

<template>
    <!-- categories -->
    <div class="container py-16">
        <h2 class="text-2xl font-medium text-gray-800 uppercase mb-6">shop by category</h2>
        <div class="grid grid-cols-3 gap-3">
            <div v-for="cat in categories" :key="cat.id" class="relative rounded-sm overflow-hidden group">
                <img :src="`../category_images/${cat.feature_image}`" :alt="`category ${cat.id}`" class="w-full">
                <a href="#"
                    class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center text-xl text-white font-roboto font-medium group-hover:bg-opacity-60 transition">{{
                        cat.name }}</a>
            </div>

        </div>
    </div>
    <!-- ./categories -->
</template>