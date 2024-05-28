<script setup>
import UserLayout from './Layouts/UserLayout.vue'
import Products from './Components/Products.vue';
import { ref, watch } from 'vue'
import { Link } from '@inertiajs/vue3'

import SecondaryButton from '@/Components/SecondaryButton.vue';
import { router, useForm } from '@inertiajs/vue3';



// reactive values
const mobileFiltersOpen = ref(false)
const selectedBrands = ref([])
const selectedCategories = ref([])
const selectedColors = ref(null)
const selectedSizes = ref(null)
const sort_by = ref('')

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

// Watch for changes to the brandand categories value
function updateFilteredProducts() {
    router.get('products', {
        brands: selectedBrands.value,
        categories: selectedCategories.value,
        size: selectedSizes.value,
        color: selectedColors.value,
        sort_by: sort_by.value
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}

watch(selectedBrands, () => {
    updateFilteredProducts()
})

watch(selectedCategories, () => {
    updateFilteredProducts()
})

watch(selectedSizes, () => {
    updateFilteredProducts()
})

watch(selectedColors, () => {
    updateFilteredProducts()
})

watch(sort_by, () => {
    updateFilteredProducts()
})

// capture form data 
const filterPrices = useForm({
    prices: [0, 1000]
})

// function to filter prices
const priceFilter = () => {
    filterPrices.transform((data) => ({
        ...data,
        prices: {
            from: filterPrices.prices[0],
            to: filterPrices.prices[1]
        }
    })).get('products', {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}


// capture data into the component
const props = defineProps({
    products: Array,
    brands: Array,
    categories: Array,
    colors: Array,
    sizes: Array
})


</script>

<template>
    <UserLayout>
        <!-- breadcrumb -->
        <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
            <div class="container py-4 flex items-center gap-3">
                <a href="../index.html" class="text-primary text-base">
                    <i class="fa-solid fa-house"></i>
                </a>
                <span class="text-sm text-gray-400">
                    <i class="fa-solid fa-chevron-right"></i>
                </span>
                <p class="text-gray-600 font-medium">Shop</p>
            </div>
            <!-- ./breadcrumb -->

            <!-- shop wrapper -->

            <div class="container grid md:grid-cols-4 grid-cols-2 gap-6 pt-4 pb-16 items-start">
                <!-- sidebar -->
                <!-- drawer init and toggle -->
                <div class="text-center md:hidden">
                    <button
                        class="text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 block md:hidden"
                        type="button" data-drawer-target="drawer-example" data-drawer-show="drawer-example"
                        aria-controls="drawer-example">
                        <ion-icon name="grid-outline"></ion-icon>
                    </button>
                </div>

                <!-- drawer component -->
                <div id="drawer-example"
                    class="fixed top-0 left-0 z-40 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white w-80 dark:bg-gray-800"
                    tabindex="-1" aria-labelledby="drawer-label">
                    <h5 id="drawer-label"
                        class="inline-flex items-center mb-4 text-base font-semibold text-gray-500 dark:text-gray-400">
                        <svg class="w-5 h-5 mr-2" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>Info
                    </h5>
                    <button type="button" data-drawer-hide="drawer-example" aria-controls="drawer-example"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close menu</span>
                    </button>
                    <div class="divide-y divide-gray-200 space-y-5">
                        <div>
                            <h3 class="text-xl text-gray-800 mb-3 uppercase font-medium">Categories</h3>
                            <div class="space-y-2">
                                <div v-for="cat in categories" :key="cat.id" class="flex items-center">
                                    <input v-model="selectedCategories" type="checkbox" name="cat-1"
                                        :id="`cat-${cat.id}`" :value="cat.id"
                                        class="text-primary focus:ring-0 rounded-sm cursor-pointer">
                                    <label :for="`cat-${cat.id}`" class="text-gray-600 ml-3 cusror-pointer">{{ cat.name
                                        }}</label>
                                    <div class="ml-auto text-gray-600 text-sm">{{ cat.products.length }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="text-xl text-gray-800 mb-3 uppercase font-medium">Brands</h3>
                            <div class="space-y-2">
                                <div v-for="brand in brands" :key="brand.id" class="flex items-center">
                                    <input v-model="selectedBrands" type="checkbox" name="brand-1"
                                        :id="`brand-${brand.id}`" :value="brand.id"
                                        class="text-primary focus:ring-0 rounded-sm cursor-pointer">
                                    <label :for="`brand-${brand.id}`" class="text-gray-600 ml-3 cusror-pointer">{{
                                        brand.name
                                    }}</label>
                                    <div class="ml-auto text-gray-600 text-sm">{{ brand.products.length }}</div>
                                </div>

                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="text-xl text-gray-800 mb-3 uppercase font-medium">Price</h3>
                            <div class="mt-4 flex items-center">
                                <input v-model="filterPrices.prices[0]" type="text" name="min" id="min"
                                    class="w-full border-gray-300 focus:border-primary rounded focus:ring-0 px-3 py-1 text-gray-600 shadow-sm"
                                    placeholder="min">
                                <span class="mx-3 text-gray-500">-</span>
                                <input v-model="filterPrices.prices[1]" type="text" name="max" id="max"
                                    class="w-full border-gray-300 focus:border-primary rounded focus:ring-0 px-3 py-1 text-gray-600 shadow-sm"
                                    placeholder="max">
                                <SecondaryButton
                                    class="mx-3 w-4 border-gray-300 focus:border-primary rounded focus:ring-0  px-3 py-1 text-gray-600 shadow-sm justify-center "
                                    @click="priceFilter">ok</SecondaryButton>
                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="text-xl text-gray-800 mb-3 uppercase font-medium">size</h3>
                            <div class="flex items-center gap-2">
                                <div v-for="size in sizes" :key="size.id" class="size-selector">
                                    <input type="radio" v-model="selectedSizes" :value="size.id" name="size"
                                        :id="`size-${size.id}`" class="hidden">
                                    <label :for="`size-${size.id}`"
                                        class="text-xs border border-gray-200 rounded-sm h-6 w-6 flex items-center justify-center cursor-pointer shadow-sm text-gray-600">{{
                                            size.name }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="text-xl text-gray-800 mb-3 uppercase font-medium">Color</h3>
                            <div class="flex items-center gap-2">
                                <div v-for="color in colors" :key="color.id" class="color-selector">
                                    <input type="radio" v-model="selectedColors" name="color" :id="color.id"
                                        class="hidden" :value="color.id">
                                    <label :for="color.id"
                                        class="border border-gray-200 rounded-sm h-6 w-6  cursor-pointer shadow-sm block"
                                        :style="`background-color: ${color.name};`"></label>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- ./sidebar -->
                <div class="col-span-1 bg-white px-4 pb-6 shadow rounded overflow-hiddenb hidden md:block">
                    <div class="divide-y divide-gray-200 space-y-5">
                        <div>
                            <h3 class="text-xl text-gray-800 mb-3 uppercase font-medium">Categories</h3>
                            <div class="space-y-2">
                                <div v-for="cat in categories" :key="cat.id" class="flex items-center">
                                    <input v-model="selectedCategories" type="checkbox" name="cat-1"
                                        :id="`cat-${cat.id}`"
                                        class="text-primary focus:ring-0 rounded-sm cursor-pointer" :value="cat.id">
                                    <label :for="`cat-${cat.id}`" class="text-gray-600 ml-3 cusror-pointer">{{ cat.name
                                        }}</label>
                                    <div class="ml-auto text-gray-600 text-sm">{{ cat.products.length }}</div>
                                </div>

                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="text-xl text-gray-800 mb-3 uppercase font-medium">Brands</h3>
                            <div class="space-y-2">
                                <div v-for="brand in brands" :key="brand.id" class="flex items-center">
                                    <input v-model="selectedBrands" type="checkbox" name="brand-1"
                                        :id="`brand-${brand.id}`"
                                        class="text-primary focus:ring-0 rounded-sm cursor-pointer" :value="brand.id">
                                    <label :for="`brand-${brand.id}`" class="text-gray-600 ml-3 cusror-pointer">{{
                                        brand.name }}</label>
                                    <div class="ml-auto text-gray-600 text-sm">{{ brand.products.length }}</div>
                                </div>

                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="text-xl text-gray-800 mb-3 uppercase font-medium">Price</h3>
                            <div class="mt-4 flex items-center">

                                <input v-model="filterPrices.prices[0]" type="text" name="min" id="min"
                                    class="w-full border-gray-300 focus:border-primary rounded focus:ring-0 px-3 py-1 text-gray-600 shadow-sm"
                                    placeholder="min">
                                <span class="mx-3 text-gray-500">-</span>
                                <input v-model="filterPrices.prices[1]" type="text" name="max" id="max"
                                    class="w-full border-gray-300 focus:border-primary rounded focus:ring-0 px-3 py-1 text-gray-600 shadow-sm"
                                    placeholder="max">
                                <SecondaryButton
                                    class="mx-3 w-4 border-gray-300 focus:border-primary rounded focus:ring-0  px-3 py-1 text-gray-600 shadow-sm justify-center "
                                    @click="priceFilter">ok</SecondaryButton>

                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="text-xl text-gray-800 mb-3 uppercase font-medium">size</h3>
                            <div class="flex items-center gap-2">
                                <div v-for="size in sizes" :key="size.id" class="size-selector">
                                    <input type="radio" v-model="selectedSizes" :value="size.id" name="size"
                                        :id="`size-${size.id}`" class="hidden">
                                    <label :for="`size-${size.id}`"
                                        class="text-xs border border-gray-200 rounded-sm h-6 w-10 flex items-center justify-center cursor-pointer shadow-sm text-gray-600">{{
                                            size.name }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="text-xl text-gray-800 mb-3 uppercase font-medium">Color</h3>
                            <div class="flex items-center gap-2">
                                <div v-for="color in colors" :key="color.id" class="color-selector">
                                    <input type="radio" v-model="selectedColors" name="color" :value="color.id"
                                        :id="color.id" class="hidden">
                                    <label :for="color.id"
                                        class="border border-gray-200 rounded-sm h-6 w-6  cursor-pointer shadow-sm block"
                                        :style="`background-color: ${color.name};`"></label>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <!-- products -->
                <div class="col-span-3">
                    <div class="flex items-center mb-4">
                        <select name="sort" id="sort" v-model="sort_by"
                            class="w-44 text-sm text-gray-600 py-3 px-4 border-gray-300 shadow-sm rounded focus:ring-primary focus:border-primary">
                            <option value="">Default sorting</option>
                            <option value="price_asc">Price low to high</option>
                            <option value="price_desc">Price high to low</option>
                            <option value="date_desc">Latest product</option>
                        </select>

                        <div class="flex gap-2 ml-auto">
                            <div
                                class="border border-primary w-10 h-9 flex items-center justify-center text-white bg-primary rounded cursor-pointer">
                                <i class="fa-solid fa-grip-vertical"></i>
                            </div>
                            <div
                                class="border border-gray-300 w-10 h-9 flex items-center justify-center text-gray-600 rounded cursor-pointer">
                                <i class="fa-solid fa-list"></i>
                            </div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-3 grid-cols-2 gap-6">
                        <div v-for="product in products.data" :key="product.id"
                            class="bg-white shadow rounded overflow-hidden group">
                            <div class="relative">
                                <img :src="`../product_images/${product.product_images[0].image}`" alt="product 1"
                                    class="w-full">
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
                                <h4
                                    class="uppercase font-medium text-xl mb-2 text-gray-800 hover:text-primary transition">
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
                    <div class=" max-w-lg  mx-auto  px-20 py-7 overflow-hidden">
                        <nav aria-label="Page navigation example">
                            <ul class="flex items-center -space-x-px h-8 text-sm">
                                <li v-if="products.links.prev !== null">
                                    <Link :href="`${products.links.prev}`"
                                        class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                    <span class="sr-only">Previous</span>
                                    <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M5 1 1 5l4 4" />
                                    </svg>
                                    </Link>
                                </li>
                                <li v-for="item in products.meta.last_page" :key="item">
                                    <Link :href="`/products?page=${item}`"
                                        class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                    {{
                                        item }}</Link>
                                </li>

                                <li v-if="products.links.next !== null">
                                    <Link :href="`${products.links.next}`"
                                        class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                    <span class="sr-only">Next</span>
                                    <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 9 4-4-4-4" />
                                    </svg>
                                    </Link>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>


                <!-- ./products -->
            </div>
            <!-- ./shop wrapper -->
        </div>
    </UserLayout>
</template>