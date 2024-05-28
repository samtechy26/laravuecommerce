<script setup>
import { router } from '@inertiajs/vue3';
import UserLayout from './Layouts/UserLayout.vue';
import { ref } from 'vue';
import { useTimeAgo } from '@/Composables/useTimeAgo'

const dialogVisible = ref(false)
const dialogVisible2 = ref(false)

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

const { timeAgo } = useTimeAgo()

const rating = ref(0)
const ratingMessage = ref('')

const possibleRatings = [1, 2, 3, 4, 5]


const hoverIndex = (index) => rating.value = index

const resetHover = () => rating.value = 0

const props = defineProps({
    product: Object
})


const currentImage = ref(null)

const ratingPercentage = (rate) => {
    let count = 0

    for (let el of props.product.ratings) {
        if (el.rating == rate) {
            count++
        }
    }

    let countPercentage = (count / props.product.ratings.length) * 100

    return countPercentage
}

const progressBar = (fun, item) => {
    if (fun(item) === 0) {
        return 0
    } else if (fun(item) <= 20) {
        return 1
    } else if (fun(item) <= 40) {
        return 2
    }
    else if (fun(item) <= 60) {
        return 3
    } else if (fun(item) <= 80) {
        return 4
    } else {
        return 5
    }
}

const handleSubmitReview = () => {
    dialogVisible.value = false
    router.post(route('product.rate'), {
        product_id: props.product.id,
        rating: rating.value,
        rating_message: ratingMessage.value
    }, {
        onSuccess: (page) => {
            Swal.fire({
                toast: true,
                icon: `${page.props.flash.success ? 'success' : 'error'}`,
                position: 'top-end',
                showConfirmButton: false,
                title: `${page.props.flash.success ? page.props.flash.success : page.props.flash.error}`
            })
        }
    })
}

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
                <p class="text-gray-600 font-medium">Product</p>
            </div>
            <!-- ./breadcrumb -->

            <div class="font-sans bg-white">
                <div class="p-6 lg:max-w-7xl max-w-4xl mx-auto">
                    <div
                        class="grid items-start grid-cols-1 lg:grid-cols-5 gap-12 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.3)] p-6">
                        <div class="lg:col-span-3 w-full lg:sticky top-0 text-center">

                            <div class="px-4 py-10 rounded-xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.3)] relative">
                                <img :src="`/product_images/${product.product_images[0].image}`" alt="Product"
                                    class="w-4/5 rounded object-cover" />
                                <button type="button" @click="addToWishList(product)" class="absolute top-4 right-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" fill="#ccc"
                                        class="mr-1 hover:fill-[#333]" viewBox="0 0 64 64">
                                        <path
                                            d="M45.5 4A18.53 18.53 0 0 0 32 9.86 18.5 18.5 0 0 0 0 22.5C0 40.92 29.71 59 31 59.71a2 2 0 0 0 2.06 0C34.29 59 64 40.92 64 22.5A18.52 18.52 0 0 0 45.5 4ZM32 55.64C26.83 52.34 4 36.92 4 22.5a14.5 14.5 0 0 1 26.36-8.33 2 2 0 0 0 3.27 0A14.5 14.5 0 0 1 60 22.5c0 14.41-22.83 29.83-28 33.14Z"
                                            data-original="#000000"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="mt-6 flex flex-wrap justify-center gap-6 mx-auto">
                                <div v-for="image in product.product_images" :key="image.id"
                                    class="rounded-xl p-4 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.3)]">
                                    <img :src="`/product_images/${image.image}`" alt="Product2"
                                        class="w-24 cursor-pointer" />
                                </div>

                            </div>
                        </div>

                        <div class="lg:col-span-2">
                            <h2 class="text-2xl font-bold text-[#333]">{{ product.title }}</h2>
                            <div class="flex flex-wrap gap-4 mt-4">
                                <p class="text-[#333] text-3xl font-semibold">${{ product.price }}</p>

                            </div>

                            <div class="flex space-x-2 mt-4">
                                <svg v-for="item in possibleRatings" :key="item" class="w-5"
                                    :class="`${item <= +product.average_rating ? 'fill-yellow-500' : 'fill-gray-400'}`"
                                    viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M7 0L9.4687 3.60213L13.6574 4.83688L10.9944 8.29787L11.1145 12.6631L7 11.2L2.8855 12.6631L3.00556 8.29787L0.342604 4.83688L4.5313 3.60213L7 0Z" />
                                </svg>

                                <h4 class="text-[#333] text-base">{{ product.ratings?.length }} Reviews</h4>
                            </div>

                            <div class="mt-10">
                                <h3 class="text-lg font-semibold text-gray-800">Choose a Color</h3>
                                <div class="flex flex-wrap gap-3 mt-4">
                                    <button v-for="color in product.colors" :key="color.id" type="button"
                                        class="w-10 h-10  border-2 border-white hover:border-gray-800 rounded-full shrink-0"
                                        :style="`background-color: ${color.name};`"></button>

                                </div>
                            </div>

                            <div class="flex flex-wrap gap-4 mt-10">
                                <button type="button"
                                    class="min-w-[200px] px-4 py-3 bg-primary hover:bg-[#111] text-white text-sm font-semibold rounded">Buy
                                    now</button>
                                <button type="button" @click="addToCart(product)"
                                    class="min-w-[200px] px-4 py-2.5 border border-primary bg-transparent hover:bg-gray-50 text-[#333] text-sm font-semibold rounded">Add
                                    to cart</button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-16 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.3)] p-6">
                        <!-- description -->
                        <div class="container pb-16">
                            <h3 class="border-b border-gray-200 font-roboto text-gray-800 pb-3 font-medium">Product
                                details</h3>
                            <div class=" pt-6">
                                <div class="text-gray-600">
                                    <p>{{ product.description }}</p>
                                </div>

                                <table class="table-auto border-collapse w-full text-left text-gray-600 text-sm mt-6">
                                    <tr>
                                        <th class="py-2 px-4 border border-gray-300 w-40 font-medium">Color</th>
                                        <th class="py-2 px-4 border border-gray-300 "><span
                                                v-for="color in product.colors" :key="color.id">{{ color.name }},</span>
                                        </th>
                                    </tr>
                                    <tr v-if="product.material">
                                        <th class="py-2 px-4 border border-gray-300 w-40 font-medium">Material</th>
                                        <th class="py-2 px-4 border border-gray-300 ">{{ product.material.name }}</th>
                                    </tr>
                                    <tr v-if="product.weight">
                                        <th class="py-2 px-4 border border-gray-300 w-40 font-medium">Weight</th>
                                        <th class="py-2 px-4 border border-gray-300 ">{{ product.weight }}kg</th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mt-16 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.3)] p-6">
                        <h3 class="text-lg font-bold text-[#333]">Reviews({{ product.ratings?.length }})</h3>
                        <div class="grid md:grid-cols-2 gap-12 mt-6">
                            <div v-if="product.ratings.length">
                                <div class="space-y-3">
                                    <div v-for="item in possibleRatings" :key="item" class="flex items-center">
                                        <p class="text-sm text-[#333] font-bold">{{ item }}</p>
                                        <svg class="w-5 fill-yellow-500 ml-1" viewBox="0 0 14 13" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7 0L9.4687 3.60213L13.6574 4.83688L10.9944 8.29787L11.1145 12.6631L7 11.2L2.8855 12.6631L3.00556 8.29787L0.342604 4.83688L4.5313 3.60213L7 0Z" />
                                        </svg>
                                        <div class="bg-gray-400 rounded w-full h-2 ml-5">
                                            <div v-if="ratingPercentage(item) > 0" class=" h-full rounded bg-primary"
                                                :class="`w-${progressBar(ratingPercentage, item)}/5`"></div>
                                        </div>
                                        <p class="text-sm text-[#333] font-bold ml-3">{{ ratingPercentage(item) }}%</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div v-if="product.ratings.length" class="flex items-start">
                                    <img src="https://readymadeui.com/team-2.webp"
                                        class="w-12 h-12 rounded-full border-2 border-white" />
                                    <div class="ml-3">
                                        <h4 class="text-sm font-bold text-[#333]">{{ product.ratings[0].user.name }}
                                        </h4>
                                        <div class="flex space-x-1 mt-1">
                                            <svg v-for="i in possibleRatings" :key="i" class="w-4"
                                                :class="`${i <= +product.ratings[0]?.rating ? 'fill-yellow-500' : 'fill-gray-400'}`"
                                                viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M7 0L9.4687 3.60213L13.6574 4.83688L10.9944 8.29787L11.1145 12.6631L7 11.2L2.8855 12.6631L3.00556 8.29787L0.342604 4.83688L4.5313 3.60213L7 0Z" />
                                            </svg>

                                            <p class="text-xs !ml-2 font-semibold text-[#333]">{{
                                                timeAgo(product.ratings[0].updated_at) }}</p>
                                        </div>
                                        <p class="text-sm mt-4 text-[#333]">{{ product.ratings[0].rating_message }}
                                        </p>
                                    </div>
                                </div>

                                <button type="button" @click="dialogVisible = true"
                                    class="w-full mt-10 px-4 py-2.5 bg-transparent hover:bg-gray-50 border border-primary text-[#333] font-bold rounded">{{
                                        product.ratings.length
                                            ? 'Read all reviews' : 'Add a review' }}</button>

                                <el-dialog v-model="dialogVisible" width="500" :before-close="handleClose">


                                    <div v-if="product.ratings.length" class="flex items-center">
                                        <div v-for="item in product.ratings" :key="item.id" class="flex items-start">
                                            <img src="https://readymadeui.com/team-2.webp"
                                                class="w-12 h-12 rounded-full border-2 border-white" />
                                            <div class="ml-3">
                                                <h4 class="text-sm font-bold text-[#333]">{{ item.user.name }}</h4>
                                                <div class="flex space-x-1 mt-1">
                                                    <svg v-for="i in possibleRatings" :key="i" class="w-4"
                                                        :class="`${i <= item?.rating ? 'fill-yellow-500' : 'fill-gray-400'}`"
                                                        viewBox="0 0 14 13" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M7 0L9.4687 3.60213L13.6574 4.83688L10.9944 8.29787L11.1145 12.6631L7 11.2L2.8855 12.6631L3.00556 8.29787L0.342604 4.83688L4.5313 3.60213L7 0Z" />
                                                    </svg>

                                                    <p class="text-xs !ml-2 font-semibold text-[#333]">{{
                                                        timeAgo(item.updated_at) }}</p>
                                                </div>
                                                <p class="text-sm mt-4 text-[#333]">{{ item.rating_message }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <template #footer>
                                        <div class="dialog-footer">
                                            <el-button @click="dialogVisible = false">Cancel</el-button>
                                            <el-button type="primary" @click="dialogVisible2 = true">
                                                Add a review
                                            </el-button>
                                        </div>
                                    </template>
                                </el-dialog>

                                <el-dialog v-model="dialogVisible2" width="500">

                                    <form class="max-w-sm mx-auto">
                                        <label for="message"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                                            review</label>
                                        <textarea id="message" rows="4" v-model="ratingMessage"
                                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Leave a comment..."></textarea>

                                        <div class="flex justify-between pt-10">
                                            <p class="font-medium">Leave a star</p>
                                            <svg v-for="i in possibleRatings" :key="i" class="w-4 h-4 me-1"
                                                :class="{ 'text-yellow-500': i <= rating, 'text-gray-300': i > rating }"
                                                @mouseover="hoverIndex(i)" @click="hoverIndex(i)" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 22 20">
                                                <path
                                                    d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                            </svg>
                                        </div>
                                    </form>
                                    <template #footer>
                                        <div class="dialog-footer">
                                            <el-button @click="dialogVisible2 = false">Cancel</el-button>
                                            <el-button type="primary" @click="handleSubmitReview">
                                                Confirm
                                            </el-button>
                                        </div>
                                    </template>
                                </el-dialog>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </UserLayout>

</template>