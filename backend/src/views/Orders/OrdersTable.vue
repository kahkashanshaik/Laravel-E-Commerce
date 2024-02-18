<template>
    <div class="bg-white shadow p-4 my-5 rounded-lg">
        <div class="flex justify-between border-b-2 pb-3">
            <div class="flex items-center">
                <span class="whitespace-nowrap pr-4">Per Page</span>
                <select @change="getOrders()" v-model="perPage" class="appearance-none relative block w-24px px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="100">100</option>
                </select>
                <span class="ml-3">Found {{ orders.total }} Products</span>
            </div>
            <div>
                <input type="text" v-model="search" @change="getOrders()" placeholder="type to search orders here" class="appearance-none relative block w-24px px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
            </div>
        </div>
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <TableHeaderCell field="id" :sorted-field="sortField" :sort-direction="sortDirection" @click="sortOrders('id')">
                    ID
                    </TableHeaderCell>
                    <TableHeaderCell :sorted-field="sortField" :sort-direction="sortDirection">
                    Customer
                    </TableHeaderCell>
                    <TableHeaderCell field="status" :sorted-field="sortField" :sort-direction="sortDirection" @click="sortOrders('status')">
                    Status
                    </TableHeaderCell>
                    <TableHeaderCell field="total_price" :sorted-field="sortField" :sort-direction="sortDirection" @click="sortOrders('total_price')">
                    Total Price
                    </TableHeaderCell>
                    <TableHeaderCell field="created_at" :sorted-field="sortField" :sort-direction="sortDirection" @click="sortOrders('created_at')">
                    Date
                    </TableHeaderCell>
                    <TableHeaderCell field="actions">
                    Actions
                    </TableHeaderCell>
                </tr>
            </thead>
            <tbody v-if="orders.loading || !orders.data.length">
                <tr>
                    <td colspan="5">
                        <Spinner v-if="orders.loading"/>
                        <p v-else>
                            No Orders Found
                        </p>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr v-for="(order, index) of orders.data">
                    <td class="border-b p-3">
                        {{ order.id }}
                    </td>
                    <td class="border-b p-3">
                        {{ order.customer.first_name }} {{ order.customer.last_name }}
                    </td>
                    <td class="border-b p-3">
                        <OrderStatus :order="order"/>
                    </td>
                    <td class="border-b p-3">
                        {{ $filters.currencyUSD(order.total_price) }}
                    </td>
                    <td class="border-b p-3 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis">
                        {{ order.created_at }}
                    </td>
                    <td class="border-b p-3">
                        <router-link :to="{name: 'app.orders.view', params: {id: order.id}}"
                       class="w-8 h-8 rounded-full text-indigo-700 border border-indigo-700 flex justify-center items-center
                        hover:text-white hover:bg-indigo-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </router-link>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-if="!orders.loading" class="flex justify-between mt-5 items-center">
            <div v-if="orders.data.length">
                <div>
                    Showing from {{ orders.from }} to {{ orders.to }}
                </div>
            </div>
            <nav v-if="orders.total > orders.limit" class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-0">
                <a href="#" v-for="(link, i) of orders.links" :key="i" :disabled="!link.url" @click="getForPage($event, link)" aria-current="page"
                 class="relative inline-flex px-4 py-2 items-center text-sm font-medium whitespace-nowrap" :class="[
                    link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' :
                    'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                   i === 0 ? 'rounded-l-md' : '',
                   i === orders.links.length - 1 ? 'rounded-l-md' : '',
                   !link.url ? 'bg-gray-100 text-gray-700' : ''
                 ]" v-html="link.label" ></a>
            </nav>
        </div>
    </div>
</template>
<script setup>
import {ref, onMounted, computed} from 'vue';
import { PRODUCTS_PER_PAGE } from '../../constants';
import store from '../../store';
import Spinner from '../../components/core/Spinner.vue';
import OrderStatus from './OrderStatus.vue';
import TableHeaderCell from '../../components/core/TableHeaderCell.vue';

const perPage = ref(PRODUCTS_PER_PAGE);
const search = ref('');
const sortField = ref('created_at');
const sortDirection = ref('desc');

const orders = computed(() => store.state.orders);
function getOrders(url = null) {
    store.dispatch('getOrders', {
        url,
        search: search.value,
        sort_field: sortField.value,
        sort_direction: sortDirection.value,
        per_page: perPage.value
    })
}

function sortOrders(field) {
    if(field === sortField.value) {
        if(sortDirection.value === 'desc') {
            sortDirection.value = 'asc';
        } else {
            sortDirection.value = 'desc';
        }
    } else {
        sortDirection.value = 'asc';
        sortField.value = field;
    }

    getOrders();
}

onMounted(() => {
    getOrders();
})

function getForPage($ev, link) {
    $ev.preventDefault();
    if(!link.url ||link.active) {
        return;
    } 
    getOrders(link.url);
}
</script>