<script setup>
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import Header from './Header.vue'

import Footer from './Footer.vue'

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();

    Swal.fire({
        toast: true,
        icon: `${page.props.flash.success ? 'success' : 'error'}`,
        position: 'top-end',
        showConfirmButton: false,
        title: `${page.props.flash.success ? page.props.flash.success : page.props.flash.error}`
    })
})

</script>

<template>

    <Header />

    <!-- main section begin -->
    <slot />
    <!-- main section end -->

    <Footer />

</template>