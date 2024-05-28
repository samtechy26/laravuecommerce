<script setup>
import UserLayout from './Layouts/UserLayout.vue';

</script>

<template>
    <UserLayout>
        <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
            <!-- Contact -->
            <div class="contain py-16">
                <div class="max-w-lg mx-auto shadow px-6 py-7 rounded overflow-hidden">
                    <h2 class="text-2xl uppercase font-medium mb-1">Contact Us</h2>

                    <form action="#" method="post" autocomplete="off">
                        <div class="space-y-2">
                            <div>
                                <label for="email" class="text-gray-600 mb-2 block">Fullname</label>
                                <input type="email" name="email" id="email"
                                    class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                                    placeholder="youremail.@domain.com">
                            </div>
                            <div>
                                <label for="email" class="text-gray-600 mb-2 block">Email address</label>
                                <input type="email" name="email" id="email"
                                    class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                                    placeholder="youremail.@domain.com">
                            </div>
                            <div>
                                <label for="password" class="text-gray-600 mb-2 block">Message</label>
                                <textarea type="text" name="password" id="password"
                                    class="block w-full border border-gray-300 px-4 py-3 text-gray-600 text-sm rounded focus:ring-0 focus:border-primary placeholder-gray-400"
                                    placeholder="Type what you want">
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