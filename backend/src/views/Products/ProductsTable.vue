<template>
    <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
        <div class="flex justify-between border-b-2 pb-3">
            <div class="flex items-center">
                <span class="whitespace-nowrap mr-3">Per Page</span>
                <select @change="getProducts()" v-model="perPage"
                    class="appearance-none relative block w-24 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="100">100</option>
                </select>
                <span class="ml-3">Found 0 Products</span>
            </div>
            <div>
                <input @change="getProducts()" v-model="search"
                    class="appearance-none relative block w-24px px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                    placeholder="Type to search products">
            </div>
        </div>
        <table class="table-auto w-full">

            <thead>
                <tr>
                    <TableHeaderCell field="id" :sorted-field="sortField" :sort-direction="sortDirection"
                        @click="sortProducts('id')">
                        ID
                    </TableHeaderCell>
                    <TableHeaderCell field="image" :sorted-field="sortField" :sort-direction="sortDirection">
                        Image
                    </TableHeaderCell>
                    <TableHeaderCell field="title" :sorted-field="sortField" :sort-direction="sortDirection"
                        @click="sortProducts('title')">
                        Title
                    </TableHeaderCell>
                    <TableHeaderCell field="price" :sorted-field="sortField" :sort-direction="sortDirection"
                        @click="sortProducts('price')">
                        Price
                    </TableHeaderCell>
                    <TableHeaderCell field="updated_at" :sorted-field="sortField" :sort-direction="sortDirection"
                        @click="sortProducts('updated_at')">
                        Last Updated At
                    </TableHeaderCell>
                    <TableHeaderCell field="actions">
                        Actions
                    </TableHeaderCell>
                </tr>
            </thead>
            <tbody v-if="products.loading || !products.data.length">
                <tr>
                    <td colspan="6">
                        <Spinner v-if="products.loading" />
                        <p v-else class="text-center py-9 text-gray-500">
                            There no products
                        </p>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr v-for="(product, index) of products.data">
                    <td class="border-b p-2">{{ product.id }}</td>
                    <td class="border-b p-2">
                        <img class="w-16 h-16 object-cover" :src="product.image_url" :alt="product.title" />
                    </td>
                    <td class="border-b p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis">
                        {{ product.title }}
                    </td>
                    <td class="border-b p-2">
                        {{ product.price }}
                    </td>
                    <td class="border-b p-2">
                        {{ product.updated_at }}
                    </td>
                    <td class="border-b p-2">
                        <Menu as="div" class="relative inline-block text-left">
                            <div>
                                <MenuButton
                                    class="inline-flex items-center justify-center rounded-full w-10 h-10 bg-black bg-opacity-0 text-sm font-medium text-white hover:bg-opacity-5 focus:bg-opacity-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75">
                                    <DotsVerticalIcon class="h-5 w-5 text-indigo-500" aria-hidden="true" />
                                </MenuButton>
                            </div>

                            <transition enter-active-class="transition duration-100 ease-out"
                                enter-from-class="transform scale-95 opacity-0"
                                enter-to-class="transform scale-100 opacity-100"
                                leave-active-class="transition duration-75 ease-in"
                                leave-from-class="transform scale-100 opacity-100"
                                leave-to-class="transform scale-95 opacity-0">
                                <MenuItems
                                    class="absolute z-10 right-0 mt-2 w-32 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <div class="px-1 py-1">
                                        <MenuItem v-slot="{ active }">
                                        <button :class="[
                                            active ? 'bg-indigo-600 text-white' : 'text-gray-900',
                                            'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                        ]" @click="editProduct(product)">
                                            <PencilIcon :active="active" class="mr-2 h-5 w-5 text-indigo-400"
                                                aria-hidden="true" />
                                            Edit
                                        </button>
                                        </MenuItem>
                                        <MenuItem v-slot="{ active }">
                                        <button :class="[
                                            active ? 'bg-indigo-600 text-white' : 'text-gray-900',
                                            'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                        ]" @click="deleteProduct(product)">
                                            <TrashIcon :active="active" class="mr-2 h-5 w-5 text-indigo-400"
                                                aria-hidden="true" />
                                            Delete
                                        </button>
                                        </MenuItem>
                                    </div>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-if="!products.loading" class="flex justify-between mt-5 items-center">
            <div v-if="products.data.length">
                Showing from {{ products.from }} to {{ products.to }}
            </div>
            <nav v-if="products.total > products.limit"
                class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <a v-for="(link, i) of products.links" :key="i" :disabled="!link.url" href="#"
                    @click="getForPage($event, link)" aria-current="page"
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium whitespace-nowrap" :class="[
                        link.active ?
                            'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' :
                            'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                        i === 0 ? 'rounded-l-md' : '',
                        i === products.links.length - 1 ? 'rounded-r-md' : '',
                        !link.url ? 'bg-gray-100 text-gray-700' : ''
                    ]" v-html="link.label">
                </a>
            </nav>
        </div>
    </div>
</template>
<script setup>
import { computed, onMounted, ref } from 'vue';
import TableHeaderCell from '../../components/core/TableHeaderCell.vue';
import { PRODUCTS_PER_PAGE } from '../../constants';
import Spinner from '../../components/core/Spinner.vue';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { DotsVerticalIcon, PencilIcon, TrashIcon } from '@heroicons/vue/outline'
import store from '../../store';

const perPage = ref(PRODUCTS_PER_PAGE);
const search = ref('');
const sortField = ref('updated_at');
const sortDirection = ref('desc');

const products = computed(() => store.state.products);

const emit = defineEmits(['clickEdit']);

function getProducts(url = null) {
    store.dispatch('getProducts', {
        url,
        search: search.value,
        per_page: perPage.value,
        sort_field: sortField.value,
        sort_direction: sortDirection.value
    })
}

onMounted(() => {
    getProducts();
})

function sortProducts(field) {
    if (field === sortField.value) {
        if (sortDirection.value === 'desc') {
            sortDirection.value = 'asc'
        } else {
            sortDirection.value = 'desc'
        }
    } else {
        sortField.value = field;
        sortDirection.value = 'asc';
    }
    getProducts();
}

function getForPage($ev, link) {
    $ev.preventDefault();
    if (!link.url || link.active) {
        return
    }
    getProducts(link.url);
}

function editProduct(product) {
    emit('clickEdit', product);
}

function deleteProduct(product) {
    if (!confirm('Are you sure you want to delete this product')) {
        return
    }
    store.dispatch('deleteProduct', product.id)
        .then(res => {
            store.state.toast.show = true;
            store.state.toast.message = "Item has been deleted successfully"
            store.dispatch('getProducts');
        });
}

</script>