<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import UserLayout from '../Layouts/UserLayout.vue';
import { computed } from 'vue';


const user = computed(() => usePage().props.auth.user)

const logout = () => {
    router.post(route('logout'))
}

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
            <p class="text-gray-600 font-medium">Profile</p>
        </div>
        <!-- ./breadcrumb -->

        <!-- wrapper -->
        <div class="container grid grid-cols-12 items-start gap-6 pt-4 pb-16">

            <!-- sidebar -->
            <div class="col-span-3">
                <div class="px-4 py-3 shadow flex items-center gap-4">
                    <div class="flex-shrink-0">
                        <img src="/images/avatar.png" alt="profile"
                            class="rounded-full w-14 h-14 border border-gray-200 p-1 object-cover">
                    </div>
                    <div class="flex-grow">
                        <p class="text-gray-600">Hello,</p>
                        <h4 class="text-gray-800 font-medium">{{ user.name }}</h4>
                    </div>
                </div>

                <div class="mt-6 bg-white shadow rounded p-4 divide-y divide-gray-200 space-y-4 text-gray-600">
                    <div class="space-y-1 pl-8">
                        <a href="#" class="relative text-primary block font-medium capitalize transition">
                            <span class="absolute -left-8 top-0 text-base">
                                <i class="fa-regular fa-address-card"></i>
                            </span>
                            Manage account
                        </a>
                        <Link :href="route('dashboard')"
                            class="relative hover:text-primary block capitalize transition">
                        Profile information
                        </Link>
                        <Link :href="route('dashboard.address')"
                            class="relative hover:text-primary block capitalize transition">
                        Manage addresses
                        </Link>

                    </div>

                    <div class="space-y-1 pl-8 pt-4">
                        <a href="#" class="relative hover:text-primary block font-medium capitalize transition">
                            <span class="absolute -left-8 top-0 text-base">
                                <i class="fa-solid fa-box-archive"></i>
                            </span>
                            My order history
                        </a>
                        <Link :href="route('dashboard.completed.orders')"
                            class="relative hover:text-primary block capitalize transition">
                        Completed Orders
                        </Link>
                        <a href="#" class="relative hover:text-primary block capitalize transition">
                            Pending Orders
                        </a>
                        <a href="#" class="relative hover:text-primary block capitalize transition">
                            My reviews
                        </a>
                    </div>

                    <div class="space-y-1 pl-8 pt-4">
                        <Link :href="route('wishlist.view')"
                            class="relative hover:text-primary block font-medium capitalize transition">
                        <span class="absolute -left-8 top-0 text-base">
                            <i class="fa-regular fa-heart"></i>
                        </span>
                        My wishlist
                        </Link>
                    </div>

                    <div class="space-y-1 pl-8 pt-4">
                        <a @click="logout" class="relative hover:text-primary block font-medium capitalize transition">
                            <span class=" cursor-pointerabsolute -left-8 top-0 text-base">
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </span>
                            Logout
                        </a>
                    </div>

                </div>
            </div>
            <!-- ./sidebar -->

            <slot />


        </div>
        <!-- ./wrapper -->
    </UserLayout>

</template>