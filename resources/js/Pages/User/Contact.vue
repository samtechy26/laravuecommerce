<script setup>
import { reactive } from 'vue';
import UserLayout from './Layouts/UserLayout.vue';
import { router } from '@inertiajs/vue3';

// reactive values
const contactForm = reactive({
    name: null,
    email: null,
    visitor_message: null
})


const sendContactEmail = () => {
    router.post(route('contact.send'), contactForm, {
        onSuccess: (page) => {
            Swal.fire({
                toast: true,
                icon: 'success',
                position: 'top-end',
                showConfirmButton: false,
                title: page.props.flash.success
            }),
                contactForm.name = '',
                contactForm.email = '',
                contactForm.visitor_message = ''

        }
    })
}

</script>

<template>
    <UserLayout>
        <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
            <!-- Contact -->
            <div class="contain py-16">
                <div class="max-w-lg mx-auto shadow px-6 py-7 rounded overflow-hidden">
                    <h2 class="text-2xl uppercase font-medium mb-1">Contact Us</h2>

                    <form @submit.prevent="sendContactEmail" autocomplete="off">
                        <div class="space-y-2">
                            <div>
                                <label for="name" class="text-gray-600 mb-2 block">Fullname</label>
                                <input type="text" name="name" id="name" v-model="contactForm.name"
                                    class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                                    placeholder="Your full name">
                            </div>
                            <div>
                                <label for="email" class="text-gray-600 mb-2 block">Email address</label>
                                <input type="email" name="email" id="email" v-model="contactForm.email"
                                    class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                                    placeholder="youremail.@domain.com">
                            </div>
                            <div>
                                <label for="message" class="text-gray-600 mb-2 block">Message</label>
                                <textarea type="text" name="message" id="message" v-model="contactForm.visitor_message"
                                    class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                                    placeholder="Type your message">
                        </textarea>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit"
                                class="block w-full py-2 text-center text-white bg-primary border border-primary rounded hover:bg-transparent hover:text-primary transition uppercase font-roboto font-medium">Send
                                Us a Message</button>
                        </div>
                    </form>


                </div>
            </div>
            <!-- ./Contact -->
        </div>

    </UserLayout>

</template>