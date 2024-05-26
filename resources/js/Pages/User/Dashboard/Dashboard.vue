<script setup>
import { router, usePage } from '@inertiajs/vue3';
import DashboardLayout from './DashboardLayout.vue'
import { reactive } from 'vue';


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
                icon: `${page.props.flash.success ? 'success' : 'error'}`,
                position: 'top-end',
                showConfirmButton: false,
                title: `${page.props.flash.success ? page.props.flash.success : page.props.flash.error}`
            }),
                passwordData.currentPassword = null,
                passwordData.newPassword = null,
                passwordData.ConfirmPassword = null


        }
    })
}

</script>

<template>
    <DashboardLayout>
        <!-- info -->
        <div class="col-span-9 ">
            <div class="shadow rounded px-6 pt-5 pb-7">
                <h4 class="text-lg font-medium capitalize mb-4">
                    Profile information
                </h4>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="first">First name</label>
                            <input v-model="profileData.firstName" type="text" name="first" id="first"
                                class="input-box">
                        </div>
                        <div>
                            <label for="last">Last name</label>
                            <input v-model="profileData.lastName" type="text" name="last" id="last" class="input-box">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="birthday">Birthday</label>
                            <input v-model="profileData.birthday" type="date" name="birthday" id="birthday"
                                class="input-box">
                        </div>
                        <div>
                            <label for="gender">Gender</label>
                            <select v-model="profileData.gender" name="gender" id="gender" class="input-box">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="email">Email Address</label>
                            <input v-model="profileData.email" type="email" name="email" id="email" class="input-box">
                        </div>
                        <div>
                            <label for="phone">Phone number</label>
                            <input v-model="profileData.phone" type="text" name="phone" id="phone" class="input-box">
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" @click="updateProfile"
                        class="py-3 px-4 text-center text-white bg-primary border border-primary rounded-md hover:bg-transparent hover:text-primary transition font-medium">save
                        changes</button>
                </div>
            </div>

            <div class="shadow rounded px-6 pt-5 pb-7">
                <h4 class="text-lg font-medium capitalize mb-4">
                    Update Password
                </h4>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="curr">Current Password</label>
                            <input v-model="passwordData.currentPassword" type="password" name="curr" id="curr"
                                class="input-box">
                        </div>

                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="new">New Password</label>
                            <input v-model="passwordData.newPassword" type="password" name="new" id="new"
                                class="input-box">
                        </div>
                        <div>
                            <label for="confirm">Confirm Password</label>
                            <input v-model="passwordData.ConfirmPassword" type="password" name="confirm" id="confirm"
                                class="input-box">
                        </div>
                    </div>


                </div>

                <div class="mt-4">
                    <button type="submit" @click="updatePassword"
                        class="py-3 px-4 text-center text-white bg-primary border border-primary rounded-md hover:bg-transparent hover:text-primary transition font-medium">save
                        changes</button>
                </div>
            </div>
        </div>
        <!-- ./info -->
    </DashboardLayout>
</template>