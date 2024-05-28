<script setup>
import UserLayout from './Layouts/UserLayout.vue';
import CartItems from './Components/CartItems.vue'
import { computed, reactive } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const carts = computed(() => usePage().props.cart.data.items);

const products = computed(() => usePage().props.cart.data.product);
const total = computed(() => usePage().props.cart.data.total);

const addressForm = reactive({
    address1: null,
    address2: null,
    state: null,
    city: null,
    zipcode: null,
    country_code: null,
    type: null
});

const addressFormFilled = computed(() => {
    return addressForm.address1 && addressForm.state && addressForm.city && addressForm.zipcode && addressForm.country_code && addressForm.type;
});

defineProps({
    userAddress: Object
});



const handleSubmit = () => {
    router.visit(route('checkout.store'), {
        method: 'post',
        data: {
            carts: carts.value,
            products: products.value,
            total: total.value,
            address: addressForm
        },

    });
};
</script>

<template>
    <UserLayout>
        <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
            <!-- breadcrumb -->
            <div class="container py-4 flex items-center gap-3">
                <a href="../index.html" class="text-primary text-base">
                    <i class="fa-solid fa-house"></i>
                </a>
                <span class="text-sm text-gray-400">
                    <i class="fa-solid fa-chevron-right"></i>
                </span>
                <p class="text-gray-600 font-medium">Checkout</p>
            </div>
            <!-- ./breadcrumb -->

            <!-- wrapper -->
            <div class="container grid grid-cols-12 items-start pb-16 pt-4 gap-6">

                <div class="col-span-4 border border-gray-200 p-4 rounded">
                    <h3 class="text-lg font-medium capitalize mb-4">Shipping Info</h3>
                    <div class="space-y-4">
                        <div class="mb-5">
                            <label for="base-input"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address 1</label>
                            <input type="text" v-model="addressForm.address1" id="base-input"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div class="mb-5">
                            <label for="base-input"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address 2</label>
                            <input type="text" v-model="addressForm.address2" id="base-input"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div class="mb-5">
                            <label for="base-input"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Country</label>
                            <input type="text" id="base-input" v-model="addressForm.country_code"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div class="mb-5">
                            <label for="base-input"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">State</label>
                            <input type="text" id="base-input" v-model="addressForm.state"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div class="mb-5">
                            <label for="base-input"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">City</label>
                            <input type="text" id="base-input" v-model="addressForm.city"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div class="mb-5">
                            <label for="base-input"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Zip Code</label>
                            <input type="text" id="base-input" v-model="addressForm.zipcode"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div class="mb-5">
                            <label for="base-input"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address Type (Home
                                | Office)</label>
                            <input v-model="addressForm.type" type="text" id="base-input"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>

                    </div>

                </div>

                <div class="col-span-8 border border-gray-200 p-4 rounded">
                    <h4 class="text-gray-800 text-lg mb-4 font-medium uppercase">order summary</h4>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-16 py-3">
                                        <span class="sr-only">Image</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Product
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Qty
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
                                <CartItems />

                            </tbody>
                        </table>
                    </div>

                    <div
                        class="flex justify-between border-b border-gray-200 mt-1 text-gray-800 font-medium py-3 uppercas">
                        <p>subtotal</p>
                        <p>${{ total }}</p>
                    </div>

                    <div
                        class="flex justify-between border-b border-gray-200 mt-1 text-gray-800 font-medium py-3 uppercas">
                        <p>shipping</p>
                        <p>{{ shipping ? shipping : 'Free' }}</p>
                    </div>

                    <div class="flex justify-between text-gray-800 font-medium py-3 uppercas">
                        <p class="font-semibold">Total</p>
                        <p>${{ shipping ? total + shipping : total }}</p>
                    </div>

                    <div class="flex items-center mb-4 mt-2">
                        <input type="checkbox" name="aggrement" id="aggrement"
                            class="text-primary focus:ring-0 rounded-sm cursor-pointer w-3 h-3">
                        <label for="aggrement" class="text-gray-600 ml-3 cursor-pointer text-sm">I agree to the <a
                                href="#" class="text-primary">terms & conditions</a></label>
                    </div>

                    <a @click="handleSubmit"
                        class="block cursor-pointer w-full py-3 px-4 text-center text-white bg-primary border border-primary rounded-md hover:bg-transparent hover:text-primary transition font-medium">Place
                        order</a>
                </div>

            </div>
        </div>

    </UserLayout>
</template>