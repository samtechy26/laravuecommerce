<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import DashboardLayout from './DashboardLayout.vue';
import { computed, ref } from 'vue';


import { ElMessageBox } from 'element-plus'

const dialogVisible = ref(false)

const handleClose = (() => {
    dialogVisible.value = false
})

const orderId = ref(null)
const total = ref(null)
const orderItems = ref([])


const openViewModal = (order) => {
    dialogVisible.value = true
    orderId.value = order.id
    orderItems.value = order.order_items
    total.value = order.total_price
}

const formatDate = (dateString) => {
    const date = new Date(dateString)
    return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
    }).format(date)
}

defineProps({
    orders: Array
})

</script>

<template>
    <DashboardLayout>
        <div class="col-span-9 shadow rounded px-6 pt-5 pb-7">


            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Order ID
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Created at
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="order in orders" :key="order.id"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Order#{{ order.id }}
                            </th>
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ formatDate(order.created_at) }}
                            </th>
                            <td class="px-6 py-4">

                                <el-button plain @click="openViewModal(order)">
                                    View Details
                                </el-button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>


            <el-dialog v-model="dialogVisible" :title="`ORDER#${orderId}`" width="500" :before-close="handleClose">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 rounded-s-lg">
                                    Product name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Qty
                                </th>
                                <th scope="col" class="px-6 py-3 rounded-e-lg">
                                    Price
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in orderItems" :key="item.id" class="bg-white dark:bg-gray-800">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ item.product.title }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ item.quantity }}
                                </td>
                                <td class="px-6 py-4">
                                    ${{ item.unit_price }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="font-semibold text-gray-900 dark:text-white">
                                <th scope="row" class="px-6 py-3 text-base">Total</th>
                                <td class="px-6 py-3"></td>
                                <td class="px-6 py-3">${{ total }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <template #footer>
                    <div class="dialog-footer">
                        <el-button @click="dialogVisible = false">Cancel</el-button>
                        <el-button type="primary" @click="dialogVisible = false">
                            Repeat
                        </el-button>
                    </div>
                </template>
            </el-dialog>

        </div>
    </DashboardLayout>
</template>