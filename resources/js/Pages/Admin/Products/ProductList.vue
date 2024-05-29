<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Plus } from '@element-plus/icons-vue';


const props = defineProps({
    products: Array
})
// Getting data into the component

const brands = usePage().props.brands
const categories = usePage().props.categories
const sizes = usePage().props.sizes
const colors = usePage().props.colors

const color_options = colors.map((el) => ({
    value: el.id,
    label: el.name,
}))

const size_options = sizes.map(el => ({
    value: el.id,
    label: el.name
}))



// handle upload of product images
const productImages = ref([])
const dialogImageUrl = ref('')


const handleFileChange = (file) => {
    productImages.value.push(file)
}

const handlePictureCardPreview = (file) => {
    dialogImageUrl.value = file.url
    dialogVisible.value = true
}

const handleRemove = (file) => {
    return null
}


// Setting conditional variables
const isAddProduct = ref(false)
const dialogVisible = ref(false)
const editMode = ref(false)

//Declairing product form data
const id = ref('')
const title = ref('')
const price = ref('')
const quantity = ref(0)
const weight = ref(0)
const description = ref('')
const product_images = ref([])
const published = ref(true)
const category_id = ref('')
const brand_id = ref('')
const product_colors = ref([])
const product_sizes = ref([])
const instock = ref('')

// reset form data
const resetFormData = () => {
    id.value = ''
    title.value = ''
    price.value = ''
    weight.value = ''
    quantity.value = 0
    description.value = ''
    productImages.value = []
    product_colors.value = []
    product_sizes.value = []
}

// open add modal
const openAddModal = () => {
    isAddProduct.value = true
    dialogVisible.value = true
    editMode.value = false

    resetFormData()
}



// add product method
const addProduct = async () => {
    const formData = new FormData()
    formData.append('title', title.value)
    formData.append('price', price.value)
    formData.append('quantity', quantity.value)
    formData.append('weight', weight.value)
    formData.append('description', description.value)
    formData.append('brand_id', brand_id.value)
    formData.append('category_id', category_id.value)


    // append product images to form data

    for (const image of productImages.value) {
        formData.append('product_images[]', image.raw)
    }

    for (const color of product_colors.value) {
        formData.append('product_colors[]', color)
    }

    for (const size of product_sizes.value) {
        formData.append('product_sizes[]', size)
    }

    console.log(formData)

    try {
        await router.post(route('admin.products.store'), formData, {
            onSuccess: page => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success
                })

                dialogVisible.value = false
                resetFormData()
            }
        })

    } catch (err) {

    }
}


//open edit form
const openEditModal = (product) => {
    editMode.value = true
    isAddProduct.value = false
    dialogVisible.value = true

    // update data
    id.value = product.id
    title.value = product.title
    price.value = product.price
    weight.value = product.weight
    quantity.value = product.quantity
    description.value = product.description
    brand_id.value = product.brand_id
    category_id.value = product.category_id

    product_images.value = product.product_images


}

// Update product Method

const updateProduct = async () => {
    const formData = new FormData()
    formData.append('title', title.value)
    formData.append('price', price.value)
    formData.append('quantity', quantity.value)
    formData.append('weight', weight.value)
    formData.append('description', description.value)
    formData.append('brand_id', brand_id.value)
    formData.append('category_id', category_id.value)


    // append product images to form data

    for (const image of productImages.value) {
        formData.append('product_images[]', image.raw)
    }

    for (const color of product_colors.value) {
        formData.append('product_colors[]', color)
    }

    for (const size of product_sizes.value) {
        formData.append('product_sizes[]', size)
    }

    formData.append('_method', 'PUT')



    try {

        await router.post(`/admin/products/update/${id.value}`, formData, {
            onSuccess: (page) => {
                dialogVisible.value = false
                resetFormData()
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

// Delete Single Product Images

const deleteImage = async (pimage, index) => {
    try {

        await router.delete(`/admin/products/image/${pimage.id}`, {
            onSuccess: (page) => {
                product_images.value.splice(index, 1)
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

    }
}

// Delete Product
const deleteProduct = (product, index) => {
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

                router.delete(`/admin/product/destroy/${product.id}`, {
                    onSuccess: (page) => {
                        this.delete(product, index)
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
    <section class="bg-gray-50 dark:bg-gray-900 py-3 sm:py-5">
        <el-dialog v-model="dialogVisible" width="700" :before-close="handleClose">
            <section class="bg-white dark:bg-gray-900">
                <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
                    <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">{{ editMode ? 'Update Product'
                        : 'Add product' }}</h2>
                    <form @submit.prevent="editMode ? updateProduct() : addProduct()">
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                            <div class="sm:col-span-2">
                                <label for="name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product
                                    Name</label>
                                <input type="text" name="name" id="name" v-model="title"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Type product name" required="">
                            </div>
                            <div class="w-full">
                                <label for="brand"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Brand</label>
                                <select id="brand" v-model="brand_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option selected="">Select Brand</option>
                                    <option v-for="brand in brands" :key="brand.id" :value="brand.id">{{ brand.name
                                        }}
                                    </option>

                                </select>
                            </div>
                            <div class="w-full">
                                <label for="price"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                                <input type="number" name="price" id="price" v-model="price"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="$2999" required="">
                            </div>
                            <div class="w-full">
                                <label for="price"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quantity</label>
                                <input type="number" name="price" id="price" v-model="quantity"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="$2999" required="">
                            </div>
                            <div>
                                <label for="category"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                                <select id="category" v-model="category_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option selected="">Select category</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label for="colors"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Colors</label>
                                <el-select-v2 v-model="product_colors" filterable :options="color_options"
                                    placeholder="Please select colors" style="width: 240px" multiple />
                            </div>
                            <div>
                                <label for="size"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sizes</label>
                                <el-select-v2 v-model="product_sizes" filterable :options="size_options"
                                    placeholder="Please select colors" style="width: 240px" multiple />
                            </div>
                            <div>
                                <label for="item-weight"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Item Weight
                                    (kg)</label>
                                <input type="number" name="item-weight" id="item-weight" v-model="weight"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="12" required="">
                            </div>
                            <div class="sm:col-span-2">
                                <label for="description"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                                <textarea id="description" rows="8" v-model="description"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Your description here"></textarea>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="description"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product
                                    Images</label>
                                <el-upload v-model:file-list="productImages" list-type="picture-card" multiple
                                    :on-preview="handlePictureCardPreview" :on-remove="handleRemove"
                                    :on-change="handleFileChange">
                                    <el-icon>
                                        <Plus />
                                    </el-icon>
                                </el-upload>

                            </div>
                            <!-- list of images for selected images -->
                            <div class="flex flex-nowrap mb-8">
                                <div v-for="(pimage, index) in product_images" :key="pimage.id" class="relative">
                                    <img class="w-24 h-24 rounded" :src="`../product_images/${pimage.image}`" alt="">
                                    <span
                                        class="absolute top-0 left-8 transform -translate-y-1/2 w-3.5 h-3.5 bg-red-400 border-2 border-white dark:border-gray-800 rounded-full">
                                        <span @click="deleteImage(pimage, index)"
                                            class="text-white font-bold absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">X</span>
                                    </span>
                                </div>
                            </div>
                            <!-- end -->
                        </div>
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                            {{ editMode ? 'Update Product' : 'Add Product' }}
                        </button>
                    </form>
                </div>
            </section>

        </el-dialog>

        <div class="px-4 mx-auto max-w-screen-2xl lg:px-12">
            <div class="relative overflow-hidden bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
                <div
                    class="flex flex-col px-4 py-3 space-y-3 lg:flex-row lg:items-center lg:justify-between lg:space-y-0 lg:space-x-4">
                    <div class="flex items-center flex-1 space-x-4">
                        <h5>
                            <span class="text-gray-500">All Products:</span>
                            <span class="dark:text-white">{{ products.meta.total }}</span>
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
                            Add new product
                        </button>
                        <button type="button"
                            class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            Export
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4">
                                    <div class="flex items-center">
                                        <input id="checkbox-all" type="checkbox"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="checkbox-all" class="sr-only">checkbox</label>
                                    </div>
                                </th>
                                <th scope="col" class="px-4 py-3">Product</th>
                                <th scope="col" class="px-4 py-3">Category</th>
                                <th scope="col" class="px-4 py-3">Stock</th>
                                <th scope="col" class="px-4 py-3">Brand</th>
                                <th scope="col" class="px-4 py-3">Sizes</th>
                                <th scope="col" class="px-4 py-3">Colors</th>
                                <th scope="col" class="px-4 py-3">Rating</th>
                                <th scope="col" class="px-4 py-3">Sales</th>
                                <th scope="col" class="px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in products.data" :key="product.id"
                                class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="w-4 px-4 py-3">
                                    <div class="flex items-center">
                                        <input id="checkbox-table-search-1" type="checkbox"
                                            onclick="event.stopPropagation()"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                    </div>
                                </td>
                                <th scope="row"
                                    class="flex items-center px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <img :src="`../product_images/${product.product_images[0].image}`"
                                        alt="iMac Front Image" class="w-auto h-8 mr-3">
                                    {{ product.title }}
                                </th>
                                <td class="px-4 py-2">
                                    <span
                                        class="bg-primary-100 justify-items-center text-primary-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-primary-900 dark:text-primary-300 whitespace-nowrap">{{
                                            product.category.name }}</span>
                                </td>
                                <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="flex items-center">
                                        <div class="inline-block w-4 h-4 mr-2 rounded-full"
                                            :class="`${product.quantity === 0 ? 'bg-red-700 ' : 'bg-green-500'}`"></div>
                                        {{ product.quantity }}
                                    </div>
                                </td>
                                <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ product.brand.name }}</td>
                                <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <span v-for="s in product.sizes" :key="s.id">{{ s.name }},</span>
                                </td>
                                <td class="px-4 py-2"><span v-for="c in product.colors" :key="c.id">{{ c.name
                                        }},</span></td>
                                <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="flex items-center">
                                        <div class="flex gap-1 text-sm ">
                                            <span v-for="item in 5" :key="item">
                                                <i class="fa-solid fa-star"
                                                    :class="`${item <= product.average_rating ? 'text-yellow-400' : 'text-gray-400'}`"></i>
                                            </span>

                                        </div>
                                        <span class="ml-1 text-gray-500 dark:text-gray-400">{{ product.average_rating
                                            }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 24 24" fill="currentColor"
                                            class="w-5 h-5 mr-2 text-gray-400" aria-hidden="true">
                                            <path
                                                d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z" />
                                        </svg>
                                        {{ product.orderItems?.length }}
                                    </div>
                                </td>

                                <td class="px-4 py-3 items-center">
                                    <button :id="`${product.id}-dropdown-button`"
                                        :data-dropdown-toggle="`${product.id}-dropdown`"
                                        class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                        type="button">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                    <div :id="`${product.id}-dropdown`"
                                        class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                            aria-labelledby="`${cat.id}-dropdown-button`">
                                            <li>
                                                <a @click="openEditModal(product)"
                                                    class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                                            </li>
                                        </ul>
                                        <div class="py-1">
                                            <a href="#" @click="deleteProduct(product, index)"
                                                class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <nav class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0"
                    aria-label="Table navigation">
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                        Showing
                        <span class="font-semibold text-gray-900 dark:text-white">{{ products?.meta.from }}-{{
                            products?.meta.to }}</span>
                        of
                        <span class="font-semibold text-gray-900 dark:text-white">{{ products?.meta.total }}</span>
                    </span>
                    <ul class="flex items-center -space-x-px h-8 text-sm">
                        <li v-if="products.links.prev !== null">
                            <Link :href="`${products.links.prev}`"
                                class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                            <span class="sr-only">Previous</span>
                            <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 1 1 5l4 4" />
                            </svg>
                            </Link>
                        </li>
                        <li v-for="item in products.meta.last_page" :key="item">
                            <Link :href="`/admin/products?page=${item}`"
                                class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                            {{
                                item }}</Link>
                        </li>

                        <li v-if="products.links.next !== null">
                            <Link :href="`${products.links.next}`"
                                class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                            <span class="sr-only">Next</span>
                            <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            </Link>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>
</template>