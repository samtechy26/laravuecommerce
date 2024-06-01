<script setup>
import { router, usePage } from '@inertiajs/vue3';
import DashboardLayout from './DashboardLayout.vue'
import { reactive, ref } from 'vue';
import { UploadFilled } from '@element-plus/icons-vue'
import { Plus } from '@element-plus/icons-vue';


defineProps({
    orders: Array
})

const profileData = reactive({
    email: usePage().props.auth.user.email,
    firstName: usePage().props.auth.user.name.split(' ')[0],
    lastName: usePage().props.auth.user.name.split(' ')[1],
    birthday: usePage().props.auth.user.profile.birthday,
    phone: usePage().props.auth.user.profile.phone,
    gender: usePage().props.auth.user.profile.gender
})

const passwordData = reactive({
    currentPassword: null,
    newPassword: null,
    ConfirmPassword: null
})

const updateProfile = () => {
    router.post(route('user.profile.update'), profileData, {
        onSuccess: page => {
            Swal.fire({
                toast: true,
                icon: 'success',
                position: 'top-end',
                showConfirmButton: false,
                title: page.props.flash.success
            })

        }
    })
}

const updatePassword = () => {
    router.patch(route('user.password.update'), passwordData, {
        onSuccess: (page) => {
            Swal.fire({
                toast: true,
                icon: page.props.flash.success ? 'success' : 'error',
                position: 'top-end',
                showConfirmButton: false,
                timer: 1000,
                title: page.props.flash.success ? page.props.flash.success : page.props.flash.error
            });

            passwordData.currentPassword = null;
            passwordData.newPassword = null;
            passwordData.ConfirmPassword = null;
        }
    });
}

</script>

<template>
    <DashboardLayout>
        <template>

        </template>
        <!-- info -->
        <div class="col-span-9 space-y-4">
            <div class="shadow rounded px-6 pt-5 pb-7">
                <h4 class="text-lg font-medium capitalize mb-4">
                    Profile information
                </h4>
                <div class="space-y-4">
                    <div class="lg:grid grid-cols-2 gap-4">
                        <div class="mb-5">
                            <label for="base-input"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First
                                name</label>
                            <input v-model="profileData.firstName" type="text" id="base-input"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div class="mb-5">
                            <label for="base-input"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last
                                name</label>
                            <input v-model="profileData.lastName" type="text" id="base-input"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                    </div>
                    <div class="lg:grid grid-cols-2 gap-4">
                        <div class="mb-5">
                            <label for="base-input"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Birthday</label>
                            <input v-model="profileData.birthday" type="date" id="base-input"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div class="mb-5">
                            <label for="gender"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gender</label>
                            <select v-model="profileData.gender" name="gender" id="gender"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>


                    </div>
                    <div class="lg:grid grid-cols-2 gap-4">
                        <div class="mb-5">
                            <label for="email"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email
                                Address</label>
                            <input v-model="profileData.email" type="email" id="email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div class="mb-5">
                            <label for="phone"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone
                                number</label>
                            <input v-model="profileData.phone" type="email" id="phone"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" @click="updateProfile"
                        class="py-3 px-4 text-center text-white bg-primary border border-primary rounded-md hover:bg-transparent hover:text-primary transition font-medium">save
                        changes</button>
                </div>
            </div>

            <div class="shadow rounded px-6 pt-5 pb-7 ">
                <h4 class="text-lg font-medium capitalize mb-4">
                    Update Password
                </h4>
                <div class="space-y-4">
                    <div class="lg:grid grid-cols-3 md:grid-cols-2  gap-4">
                        <div class="mb-5">
                            <label for="currpassword"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current
                                Password</label>
                            <input v-model="passwordData.currentPassword" type="password" id="currpassword"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>

                        <div class="mb-5">
                            <label for="new" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">New
                                Password</label>
                            <input v-model="passwordData.newPassword" type="password" id="new"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>

                        <div class="mb-5">
                            <label for="confirm"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm
                                Password</label>
                            <input v-model="passwordData.ConfirmPassword" type="confirm" id="phone"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>

                    </div>
                    <div class="mt-4">
                        <button type="submit" @click="updatePassword"
                            class="py-3 px-4 text-center text-white bg-primary border border-primary rounded-md hover:bg-transparent hover:text-primary transition font-medium">save
                            changes</button>
                    </div>
                </div>

            </div>
        </div>
        <!-- ./info -->
    </DashboardLayout>
</template>