<template>
    <div class="mb-3 flex items-center justify-between">
        <h1 class="text-3xl font-semibold">
            Products
        </h1>
        <button type="button" @click="showAddNewModal()"
            class="py-4 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Add New Products
        </button>
    </div>
    <ProductsTable @clickEdit="editProduct" />
    <ProductModal v-model="showProductModel" :product="productModel" @close="onModelClose" />
</template>

<script setup>
import { computed, ref } from 'vue';
import ProductsTable from './ProductsTable.vue';
import ProductModal from './ProductModal.vue';
import store from '../../store';



const DEFAULT_PRODUCT = {
    id: '',
    title: '',
    description: '',
    image: '',
    price: '',
}

const productModel = ref({ ...DEFAULT_PRODUCT });
const showProductModel = ref(false)

const products = computed(() => store.state.products);

function showAddNewModal() {
    showProductModel.value = true;
}

function onModelClose() {
    productModel.value = { ...DEFAULT_PRODUCT }
}

function editProduct(p) {
    store.dispatch('getProduct', p.id)
        .then(({ data }) => {
            productModel.value = data
            showAddNewModal();
        })
}



</script>