<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import UserLayout from '../Layouts/UserLayout.vue';
import { computed, reactive, ref } from 'vue';
import { UploadFilled } from '@element-plus/icons-vue'
import { Plus } from '@element-plus/icons-vue';


const user = computed(() => usePage().props.auth.user)



const profileImages = ref([])
const dialogVisible = ref(false)

const handleFileChange = (file) => {
    profileImages.value.push(file)
}

const handlePictureCardPreview = (file) => {
    dialogImageUrl.value = file.url
    dialogVisible.value = true
}

const handleClose = () => {
    ElMessageBox.confirm('Are you sure to close this dialog?')
        .then(() => {
            done()
        })
        .catch(() => {
            // catch error
        })
}

const handleProfileImage = () => {
    const data = new FormData()

    for (const image of profileImages.value) {
        data.append('profile_images[]', image.raw)
    }

    data.append('_method', 'PATCH')

    router.post(route('user.profile.image.update'), data, {
        onSuccess: page => {
            Swal.fire({
                toast: true,
                icon: 'success',
                position: 'top-end',
                showConfirmButton: false,
                title: page.props.flash.success
            })

            dialogVisible.value = false
        }
    })

}



const logout = () => {
    router.post(route('logout'))
}

</script>

<template>
    <UserLayout>
        <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
            <!-- breadcrumb -->
            <div class="container  flex items-center gap-3 py-10">
                <a href="../index.html" class="text-primary text-base">
                    <i class="fa-solid fa-house"></i>
                </a>
                <span class="text-sm text-gray-400">
                    <i class="fa-solid fa-chevron-right"></i>
                </span>
                <p class="text-gray-600 font-medium">Profile</p>
            </div>
            <!-- ./breadcrumb -->


            <el-dialog v-model="dialogVisible" title="Tips" width="500" :before-close="handleClose">
                <el-upload v-model:file-list="profileImages" list-type="picture-card"
                    :on-preview="handlePictureCardPreview" :on-remove="handleRemove" :on-change="handleFileChange">
                    <el-icon>
                        <Plus />
                    </el-icon>
                </el-upload>
                <template #footer>
                    <div class="dialog-footer">
                        <el-button @click="dialogVisible = false">Cancel</el-button>
                        <el-button type="primary" @click="handleProfileImage">
                            Confirm
                        </el-button>
                    </div>
                </template>
            </el-dialog>

            <!-- wrapper -->
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
                                <Link :href="route('dashboard.pending.orders')"
                                    class="relative hover:text-primary block capitalize transition">
                                Pending Orders
                                </Link>
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
                                <a @click="logout"
                                    class="relative hover:text-primary block font-medium capitalize transition">
                                    <span class=" cursor-pointerabsolute -left-8 top-0 text-base">
                                        <i class="fa-solid fa-right-from-bracket"></i>
                                    </span>
                                    Logout
                                </a>
                            </div>

                        </div>

                    </div>

                </div>
                <div class="col-span-1 bg-white px-4 pb-6 pt-6 shadow rounded overflow-hiddenb hidden md:block">
                    <div class="divide-y divide-gray-200 space-y-5">


                        <div
                            class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <div class="flex justify-end px-4 pt-4">
                                <button id="dropdownButton" @click="dialogVisible = true"
                                    data-dropdown-toggle="dropdown"
                                    class="inline-block text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-1.5"
                                    type="button">
                                    <span class="sr-only">Upload profile Image</span>
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 16 3">
                                        <path
                                            d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z" />
                                    </svg>
                                </button>
                            </div>
                            <div class="flex flex-col items-center pb-10">
                                <img class="w-24 h-24 mb-3 rounded-full shadow-lg"
                                    :src="`/profile_images/${user.profile?.profile_image}`" alt="Bonnie image" />
                                <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ user.name }}</h5>

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
                                <Link :href="route('dashboard.pending.orders')"
                                    class="relative hover:text-primary block capitalize transition">
                                Pending Orders
                                </Link>
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
                                <a @click="logout"
                                    class="relative hover:text-primary block font-medium capitalize transition">
                                    <span class=" cursor-pointerabsolute -left-8 top-0 text-base">
                                        <i class="fa-solid fa-right-from-bracket"></i>
                                    </span>
                                    Logout
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- ./sidebar -->
                <div class="col-span-3">
                    <slot />
                </div>
            </div>
            <!-- ./wrapper -->
        </div>

    </UserLayout>

</template>