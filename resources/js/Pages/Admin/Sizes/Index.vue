<script setup>
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AdminLayout from '../Components/AdminLayout.vue';


defineProps({
    sizes: Array
})
// Setting conditional variables

const IsAddingSize = ref(false)
const dialogVisible = ref(false)
const editMode = ref(false)

const sizeId = ref(null)
const sizeName = ref(null)

const resetData = () => {
    sizeId.value = null
    sizeName.value = null
}

const openAddModal = () => {
    IsAddingSize.value = true
    editMode.value = false
    dialogVisible.value = true
    resetData()
}

const handleAddSize = async () => {
    const data = new FormData()
    if (sizeName.value !== null) {
        data.append('name', sizeName.value)
        try {
            await router.post(route('admin.sizes.store'), data, {
                onSuccess: page => {
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        text: 'brand added successfully',
                        position: 'top-end',
                        showConfirmButton: false,
                        title: page.props.flash.sucess
                    })

                    dialogVisible.value = false
                    resetData()
                }
            })
        }
        catch (err) {
            console.log(err)
        }


    }
}

const openEditModal = (size) => {
    editMode.value = true
    IsAddingSize.value = false
    dialogVisible.value = true

    // update data
    sizeId.value = size.id
    sizeName.value = size.name

}

const handleUpdateSize = async () => {
    const data = new FormData()
    data.append('id', sizeId.value)
    data.append('name', sizeName.value)
    data.append('_method', 'PATCH')

    try {
        router.post(route('admin.sizes.update', { id: sizeId.value }), data, {
            onSuccess: (page) => {
                dialogVisible.value = false
                resetData()
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success
                })
            }
        })

    } catch (err) {
        console.log(err)
    }

}

const handleDeleteSize = async (size, index) => {
    Swal.fire({
        title: 'Are you sure ?',
        text: 'This action cannot be undone',
        icon: 'warning',
        showConfirmButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'no',
        confirmButtonText: 'yes delete'
    }).then(result => {
        if (result.isConfirmed) {
            try {

                router.delete(route('admin.sizes.delete', { id: size.id }), {
                    onSuccess: (page) => {
                        this.delete(size, index)
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            position: 'top-end',
                            showConfirmButton: false,
                            title: page.props.flash.success
                        })
                    }
                })
            } catch (err) {
                console.log(err)
            }
        }
    })
}
</script>

<template>
    <AdminLayout>
        <el-dialog v-model="dialogVisible" :title="editMode ? 'Edit Size' : 'Add Size'" width="500"
            :before-close="handleClose">
            <form @submit.prevent="editMode ? handleUpdateSize() : handleAddSize()">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <div class="sm:col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Size
                            Name</label>
                        <input type="text" name="name" id="name" v-model="sizeName"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Type size name" required="">
                    </div>
                </div>
                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-black  rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-primary-900 hover:bg-primary-600">
                    {{ editMode ? 'Update Size' : 'Add Size' }}
                </button>
            </form>
        </el-dialog>
        <div
            class="flex flex-col px-4 py-3 space-y-3 lg:flex-row lg:items-center lg:justify-between lg:space-y-0 lg:space-x-4">
            <div class="flex items-center flex-1 space-x-4">
                <h5>
                    <span class="text-gray-500">All Sizes:</span>
                    <span class="dark:text-white">{{ sizes.length }}</span>
                </h5>

            </div>
            <div
                class="flex flex-col flex-shrink-0 space-y-3 md:flex-row md:items-center lg:justify-end md:space-y-0 md:space-x-3">
                <button type="button" @click="openAddModal"
                    class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                    </svg>
                    Add new Size
                </button>

            </div>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4">
                            <div class="flex items-center">
                                <input id="checkbox-all-search" type="checkbox"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checkbox-all-search" class="sr-only">checkbox</label>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Size name
                        </th>
                        <th scope="2col" class="px-6 py-3 col-span-2">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="size in sizes" :key="size.id"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="w-4 p-4">
                            <div class="flex items-center">
                                <input id="checkbox-table-search-1" type="checkbox"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                            </div>
                        </td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ size.name }}
                        </th>
                        <td class="px-4 py-3 items-center">
                            <button :id="`${size.id}-dropdown-button`" :data-dropdown-toggle="`${size.id}-dropdown`"
                                class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                type="button">
                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                </svg>
                            </button>
                            <div :id="`${size.id}-dropdown`"
                                class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                    :aria-labelledby="`${size.id}-dropdown-button`">
                                    <li>
                                        <a @click="openEditModal(size)"
                                            class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                                    </li>
                                </ul>
                                <div class="py-1">
                                    <a href="#" @click="handleDeleteSize(size, index)"
                                        class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                                </div>
                            </div>
                        </td>

                    </tr>

                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>