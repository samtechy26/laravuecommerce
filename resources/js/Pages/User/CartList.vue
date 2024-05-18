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
    });
};

const remove = (product) => {
    router.delete(route('cart.delete', product));
};

const handleSubmit = () => {
    router.visit(route('checkout.store'), {
        method: 'post',
        data: {
            carts: carts.value,
            products: products.value,
            total: total.value,
            address: addressForm
        }
    });
};
</script>

<template>
    <UserLayout>
        <section class="text-gray-600 body-font relative">
            <div class="container px-5 py-24 mx-auto flex sm:flex-nowrap flex-wrap">
                <div class="lg:w-2/3 md:w-1/2 rounded-lg overflow-hidden sm:mr-10 p-10 flex  justify-start relative">



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
                                <tr v-for="product in products" :key="product.id"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="p-4">
                                        <img v-if="product.product_images.length"
                                            :src="`/${product.product_images[0].image}`"
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
                                            <button
                                                @click.prevent="update(product, carts[itemId(product.id)].quantity - 1)"
                                                :disabled="carts[itemId(product.id)].quantity <= 1"
                                                :class="[carts[itemId(product.id)].quantity > 1 ? 'cursor-pointer text-blue-600' : 'cursor-not-allowed text-gray-300 dark:text-gray-500', 'inline-flex items-center justify-center p-1 me-3 text-sm font-medium h-6 w-6 text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700']"
                                                type="button">
                                                <span class="sr-only">Quantity button</span>
                                                <svg class="w-3 h-3" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                                                </svg>
                                            </button>
                                            <div>
                                                <input type="number" id="first_product"
                                                    v-model="carts[itemId(product.id)].quantity"
                                                    class="bg-gray-50 w-14 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-2.5 py-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    placeholder="1" required />
                                            </div>
                                            <button
                                                @click.prevent="update(product, carts[itemId(product.id)].quantity + 1)"
                                                class="inline-flex items-center justify-center h-6 w-6 p-1 ms-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                                                type="button">
                                                <span class="sr-only">Quantity button</span>
                                                <svg class="w-3 h-3" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
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



                </div>
                <div class="lg:w-1/3 md:w-1/2 bg-white flex flex-col md:ml-auto w-full md:py-8 mt-8 md:mt-0">
                    <h2 class="text-gray-900 text-lg mb-1 font-medium title-font">Cart Summary</h2>
                    <p class="leading-relaxed mb-5 text-gray-600">total: {{ total }}</p>
                    <div v-if="userAddress">
                        <h2 class="text-gray-900 text-lg mb-1 font-medium title-font">Shipping Address</h2>
                        <p class="leading-relaxed mb-5 text-gray-600">{{ userAddress.address1 }}, {{ userAddress.state
                            }}, {{ userAddress.zipcode }}</p>
                        <p class="leading-relaxed mb-5 text-gray-600">Or You can enter a new address below</p>
                    </div>
                    <div v-else>
                        <p class="leading-relaxed mb-5 text-gray-600">Enter Shipping Address to Continue</p>
                    </div>



                    <div class="relative mb-4">
                        <label for="address1" class="leading-7 text-sm text-gray-600">Address 1</label>
                        <input type="text" id="address1" v-model="addressForm.address1"
                            class="w-full bg-white rounded border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                    <div class="relative mb-4">
                        <label for="address2" class="leading-7 text-sm text-gray-600">Address 2</label>
                        <input type="text" id="address2" v-model="addressForm.address2"
                            class="w-full bg-white rounded border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                    <div class="relative mb-4">
                        <label for="city" class="leading-7 text-sm text-gray-600">City</label>
                        <input type="text" id="city" v-model="addressForm.city"
                            class="w-full bg-white rounded border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                    <div class="relative mb-4">
                        <label for="state" class="leading-7 text-sm text-gray-600">State</label>
                        <input type="text" id="state" v-model="addressForm.state"
                            class="w-full bg-white rounded border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                    <div class="relative mb-4">
                        <label for="zipcode" class="leading-7 text-sm text-gray-600">Zipcode</label>
                        <input type="text" id="zipcode" v-model="addressForm.zipcode"
                            class="w-full bg-white rounded border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                    <div class="relative mb-4">
                        <label for="country" class="leading-7 text-sm text-gray-600">Country</label>
                        <input type="text" id="country" v-model="addressForm.country_code"
                            class="w-full bg-white rounded border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                    <div class="relative mb-4">
                        <label for="type" class="leading-7 text-sm text-gray-600">Address Type</label>
                        <input type="text" id="type" v-model="addressForm.type"
                            class="w-full bg-white rounded border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>

                    <div v-if="userAddress">
                        <button type="submit" @click="handleSubmit"
                            class="text-white bg-blue-500 border-0 py-2 px-6 focus:outline-none hover:bg-blue-600 rounded text-lg">Checkout</button>
                    </div>
                    <div v-else>
                        <button v-if="addressFormFilled" type="submit" @click="handleSubmit"
                            class="text-white bg-blue-500 border-0 py-2 px-6 focus:outline-none hover:bg-blue-600 rounded text-lg">Checkout</button>
                        <button v-else disabled
                            class="text-white bg-gray-500 border-0 py-2 px-6 focus:outline-none rounded text-lg">Complete
                            form to checkout</button>
                    </div>

                    <p class="text-xs text-gray-500 mt-3">Continue Shopping</p>
                </div>
            </div>
        </section>
    </UserLayout>
</template>