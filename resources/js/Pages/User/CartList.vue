<script setup>
import UserLayout from './Layouts/UserLayout.vue';
import { computed, reactive } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const pageProps = usePage().props;

const carts = computed(() => pageProps.cart.data.items);
const products = computed(() => pageProps.cart.data.product);
const total = computed(() => pageProps.cart.data.total);

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

const handleSubmit = () => {
    router.visit(route('checkout.store'), {
        method: 'post',
        data: {
            carts: carts.value,
            products: products.value,
            total: total.value,
            address: addressForm
        },

    }, {
        preserveState: true,
        preserveScroll: true,
        replace: truepr
    });
};
</script>

<template>
    <UserLayout>
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
                <h3 class="text-lg font-medium capitalize mb-4">Checkout</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="first-name" class="text-gray-600">First Name <span
                                    class="text-primary">*</span></label>
                            <input type="text" name="first-name" id="first-name" class="input-box">
                        </div>
                        <div>
                            <label for="last-name" class="text-gray-600">Last Name <span
                                    class="text-primary">*</span></label>
                            <input type="text" name="last-name" id="last-name" class="input-box">
                        </div>
                    </div>
                    <div>
                        <label for="company" class="text-gray-600">Company</label>
                        <input type="text" name="company" id="company" class="input-box">
                    </div>
                    <div>
                        <label for="region" class="text-gray-600">Country/Region</label>
                        <input type="text" name="region" id="region" class="input-box">
                    </div>
                    <div>
                        <label for="address" class="text-gray-600">Street address</label>
                        <input type="text" name="address" id="address" class="input-box">
                    </div>
                    <div>
                        <label for="city" class="text-gray-600">City</label>
                        <input type="text" name="city" id="city" class="input-box">
                    </div>
                    <div>
                        <label for="phone" class="text-gray-600">Phone number</label>
                        <input type="text" name="phone" id="phone" class="input-box">
                    </div>
                    <div>
                        <label for="email" class="text-gray-600">Email address</label>
                        <input type="email" name="email" id="email" class="input-box">
                    </div>
                    <div>
                        <label for="company" class="text-gray-600">Company</label>
                        <input type="text" name="company" id="company" class="input-box">
                    </div>
                </div>

            </div>

            <div class="col-span-8 border border-gray-200 p-4 rounded">
                <h4 class="text-gray-800 text-lg mb-4 font-medium uppercase">order summary</h4>
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
                            <tr v-for="product in products" :key="product.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="p-4">
                                    <img v-if="product.product_images.length"
                                        :src="`../product_images/${product.product_images[0].image}`"
                                        class="w-16 md:w-32 max-w-full max-h-full" alt="Apple Watch">
                                    <img v-else
                                        src="https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg"
                                        alt="image"
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
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 18 2">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                                            </svg>
                                        </button>
                                        <div>
                                            <input type="number" :id="product.id"
                                                v-model="carts[itemId(product.id)].quantity"
                                                class="bg-gray-50 w-14 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-2.5 py-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="1" required />
                                        </div>
                                        <button @click.prevent="update(product, carts[itemId(product.id)].quantity + 1)"
                                            class="inline-flex items-center justify-center h-6 w-6 p-1 ms-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                                            type="button">
                                            <span class="sr-only">Quantity button</span>
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 18 18">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
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

                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between border-b border-gray-200 mt-1 text-gray-800 font-medium py-3 uppercas">
                    <p>subtotal</p>
                    <p>${{ total }}</p>
                </div>

                <div class="flex justify-between border-b border-gray-200 mt-1 text-gray-800 font-medium py-3 uppercas">
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
                    <label for="aggrement" class="text-gray-600 ml-3 cursor-pointer text-sm">I agree to the <a href="#"
                            class="text-primary">terms & conditions</a></label>
                </div>

                <a @click="handleSubmit"
                    class="block w-full py-3 px-4 text-center text-white bg-primary border border-primary rounded-md hover:bg-transparent hover:text-primary transition font-medium">Place
                    order</a>
            </div>

        </div>

    </UserLayout>
</template>