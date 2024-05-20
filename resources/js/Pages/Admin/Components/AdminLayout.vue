<template>
    <Navbar />
    <Sidebar />

    <div class="p-4 sm:ml-64">
        <slot />
    </div>

</template>

<script setup>
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import Navbar from './Navbar.vue'
import Sidebar from './Sidebar.vue'
import Header from '../../User/Layouts/Header.vue'

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})
</script>

<style scoped></style>