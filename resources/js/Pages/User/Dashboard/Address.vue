<script setup>
import { router, usePage } from '@inertiajs/vue3';
import DashboardLayout from './DashboardLayout.vue'
import { reactive } from 'vue';

const props = defineProps({
    address: Object
})

const addressForm = reactive({
    address1: props.address?.address1,
    address2: props.address.address2,
    state: props.address?.state,
    city: props.address?.city,
    zipcode: props.address?.zipcode,
    country_code: props.address?.country_code,
    type: props.address?.type
});

const updateAddress = () => {
    router.patch(route('dashboard.address.update'), addressForm, {
        onSuccess: page => {
            Swal.fire({
                toast: true,
                icon: 'success',
                position: 'top-end',
                showConfirmButton: false,
                title: page.props.flash.success
            })

        },
        preserveScroll: true,
        preserveState: true
    })
}

</script>

<template>
    <DashboardLayout>
        <!-- info -->
        <div class="col-span-9 shadow rounded px-6 pt-5 pb-7">
            <h4 class="text-lg font-medium capitalize mb-4">
                Address information
            </h4>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="address1" class="text-gray-600">Address 1</label>
                        <span class="text-primary">*</span>
                        <input v-model="addressForm.address1" type="text" name="address1" id="country"
                            class="input-box">
                    </div>
                    <div>
                        <label for="address2" class="text-gray-600">Address 2</label>
                        <span class="text-primary">*</span>
                        <input v-model="addressForm.address2" type="text" name="address2" id="state" class="input-box"
                            required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="country" class="text-gray-600">Country</label>
                        <span class="text-primary">*</span>
                        <input v-model="addressForm.country_code" type="text" name="country" id="country"
                            class="input-box">
                    </div>
                    <div>
                        <label for="state" class="text-gray-600">State</label>
                        <span class="text-primary">*</span>
                        <input v-model="addressForm.state" type="text" name="state" id="state" class="input-box"
                            required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="city" class="text-gray-600">City</label>
                        <span class="text-primary">*</span>
                        <input v-model="addressForm.city" type="text" name="city" id="city" class="input-box" required>
                    </div>
                    <div>
                        <label for="zip" class="text-gray-600">Zip Code</label>
                        <span class="text-primary">*</span>
                        <input v-model="addressForm.zipcode" type="text" name="zip" id="zip" class="input-box" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="type" class="text-gray-600">Address Type (Home/Office)</label>
                        <span class="text-primary">*</span>
                        <input v-model="addressForm.type" type="text" name="type" id="type" class="input-box" required>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" @click="updateAddress"
                    class="py-3 px-4 text-center text-white bg-primary border border-primary rounded-md hover:bg-transparent hover:text-primary transition font-medium">save
                    changes</button>
            </div>
        </div>
        <!-- ./info -->
    </DashboardLayout>
</template>