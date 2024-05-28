<script setup>
import { Link } from '@inertiajs/vue3';

</script>

<template>

    <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
        <!-- ads -->

        <Link :href="route('products.index')">
        <img src="images/offer.jpg" alt="ads" class="w-full">
        </Link>

        <!-- ./ads -->
    </div>


</template>