<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

import UserLayout from '../User/Layouts/UserLayout.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <UserLayout>
        <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
            <!-- Register -->
            <div class="contain py-16">
                <div class="max-w-lg mx-auto shadow px-6 py-7 rounded overflow-hidden">
                    <h2 class="text-2xl uppercase font-medium mb-1">Create an account</h2>
                    <p class="text-gray-600 mb-6 text-sm">
                        Register for new cosutumer
                    </p>
                    <form @submit.prevent="submit" autocomplete="off">
                        <div class="space-y-2">
                            <div>
                                <label for="name" class="text-gray-600 mb-2 block">Full Name</label>
                                <input type="text" name="name" id="name" v-model="form.name"
                                    class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                                    placeholder="fulan fulana">
                            </div>
                            <div>
                                <label for="email" class="text-gray-600 mb-2 block">Email address</label>
                                <input type="email" name="email" id="email" v-model="form.email"
                                    class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                                    placeholder="youremail.@domain.com">
                            </div>
                            <div>
                                <label for="password" class="text-gray-600 mb-2 block">Password</label>
                                <input type="password" name="password" id="password" v-model="form.password"
                                    class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                                    placeholder="*******">
                            </div>
                            <div>
                                <label for="confirm" class="text-gray-600 mb-2 block">Confirm password</label>
                                <input type="password" name="confirm" id="confirm" v-model="form.password_confirmation"
                                    class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                                    placeholder="*******">
                            </div>
                        </div>
                        <div class="mt-6">
                            <div class="flex items-center">
                                <input type="checkbox" name="aggrement" id="aggrement"
                                    class="text-primary focus:ring-0 rounded-sm cursor-pointer">
                                <label for="aggrement" class="text-gray-600 ml-3 cursor-pointer">I have read and agree
                                    to
                                    the <a href="#" class="text-primary">terms & conditions</a></label>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit"
                                class="block w-full py-2 text-center text-white bg-primary border border-primary rounded hover:bg-transparent hover:text-primary transition uppercase font-roboto font-medium">create
                                account</button>
                        </div>
                    </form>

                    <!-- Register with -->
                    <div class="mt-6 flex justify-center relative">
                        <div class="text-gray-600 uppercase px-3 bg-white z-10 relative">Or signup with</div>
                        <div class="absolute left-0 top-3 w-full border-b-2 border-gray-200"></div>
                    </div>
                    <div class="mt-4 flex gap-4">
                        <a href="#"
                            class="w-1/2 py-2 text-center text-white bg-blue-800 rounded uppercase font-roboto font-medium text-sm hover:bg-blue-700">facebook</a>
                        <a href="#"
                            class="w-1/2 py-2 text-center text-white bg-red-600 rounded uppercase font-roboto font-medium text-sm hover:bg-red-500">google</a>
                    </div>
                    <!-- ./Register with -->

                    <p class="mt-4 text-center text-gray-600">Already have account?
                        <Link :href="route('login')" class="text-primary">Login now</Link>
                    </p>
                </div>
            </div>
            <!-- ./Register -->
        </div>
    </UserLayout>
</template>
