<script setup>
import UserLayout from './Layouts/UserLayout.vue'
import { router, Link } from '@inertiajs/vue3';
import Hero from './Layouts/Hero.vue'
import Products from './Components/Products.vue';
import Features from './Components/Features.vue';
import Categories from './Components/Categories.vue';
import NewArrivals from './Components/NewArrivals.vue';
import Advert from './Components/Advert.vue';
import BestProducts from './Components/BestProducts.vue';

defineProps({
    products: Array,
    categories: Array,
    top_products: Array
})



</script>

<template>
    <UserLayout>
        <Hero />
        <Features />
        <Categories :categories="categories" />
        <NewArrivals :top_products="top_products" />
        <Advert />
        <BestProducts :products="products" />
    </UserLayout>

</template>