<template>
    <!-- component -->
 <div v-if="order" class="py-5 px-4 md:px-6 2xl:px-20 2xl:container 2xl:mx-auto">
  <!--- more free and premium Tailwind CSS components at https://tailwinduikit.com/ --->

  <div class="flex justify-between items-center">
    <div class="flex justify-start item-start space-y-2 flex-col">
        <h1 class="text-3xl dark:text-white lg:text-4xl font-semibold leading-7 lg:leading-9 text-gray-800">Order #13432</h1>
        <p class="text-base dark:text-gray-300 font-medium leading-6 text-gray-600">21st Mart 2021 at 10:34 PM</p>
    </div>
    <OrderStatus :order="order"/>
  </div>
  <div class="mt-10 flex flex-col xl:flex-row jusitfy-center items-stretch w-full xl:space-x-8 space-y-4 md:space-y-6 xl:space-y-0">
    <div class="flex flex-col justify-start items-start w-full space-y-4 md:space-y-6 xl:space-y-8">
      <div class="flex flex-col justify-start items-start dark:bg-gray-800 bg-gray-50 px-4 py-4 md:py-6 md:p-6 xl:p-8 w-full">
        <p class="text-lg md:text-xl dark:text-white font-semibold leading-6 xl:leading-5 text-gray-800">Customer’s Cart</p>
        <div v-for="item of order.items" class="mt-4 md:mt-6 flex flex-col md:flex-row justify-start items-start md:items-center md:space-x-6 xl:space-x-8 w-full">
          <div class="pb-4 md:pb-8 w-full md:w-40">
            <img class="w-full hidden md:block" :src="item.product.image" alt="dress" />
            <img class="w-full md:hidden" :src="item.product.image" alt="dress" />
          </div>
          <div class="border-b border-gray-200 md:flex-row flex-col flex justify-between items-start w-full pb-8 space-y-4 md:space-y-0">
            <div class="w-full flex flex-col justify-start items-start space-y-8">
              <h3 class=" dark:text-white text-lg font-semibold leading-6 text-gray-800">{{ item.product.name }}</h3>
              
            </div>
            <div class="flex justify-between space-x-8 items-start w-full">
              <p class="text-base dark:text-white xl:text-lg leading-6">${{ item.product.price }} </p>
              <p class="text-base dark:text-white xl:text-lg leading-6 text-gray-800">{{ item.quantity }}</p>
              <p class="text-base dark:text-white xl:text-lg font-semibold leading-6 text-gray-800">${{ item.unit_price }}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="flex justify-center flex-col md:flex-row flex-col items-stretch w-full space-y-4 md:space-y-0 md:space-x-6 xl:space-x-8">
        <div class="flex flex-col px-4 py-6 md:p-6 xl:p-8 w-full bg-gray-50 dark:bg-gray-800 space-y-6">
          <h3 class="text-xl dark:text-white font-semibold leading-5 text-gray-800">Summary</h3>
          <div class="flex justify-center items-center w-full space-y-4 flex-col border-gray-200 border-b pb-4">
            <div class="flex justify-between w-full">
              <p class="text-base dark:text-white leading-4 text-gray-800">Subtotal</p>
              <p class="text-base dark:text-gray-300 leading-4 text-gray-600">${{ order.total_price }}</p>
            </div>
            
            <div class="flex justify-between items-center w-full">
              <p class="text-base dark:text-white leading-4 text-gray-800">Shipping</p>
              <p class="text-base dark:text-gray-300 leading-4 text-gray-600">$0</p>
            </div>
          </div>
          <div class="flex justify-between items-center w-full">
            <p class="text-base dark:text-white font-semibold leading-4 text-gray-800">Total</p>
            <p class="text-base dark:text-gray-300 font-semibold leading-4 text-gray-600">${{ order.total_price }}</p>
          </div>
        </div>
      </div>
    </div>
    <div class="bg-gray-50 dark:bg-gray-800 w-full xl:w-96 flex justify-between items-center md:items-start px-4 py-6 md:p-6 xl:p-8 flex-col">
      <h3 class="text-xl dark:text-white font-semibold leading-5 text-gray-800">Customer</h3>
      <div class="flex flex-col md:flex-row xl:flex-col justify-start items-stretch h-full w-full md:space-x-6 lg:space-x-8 xl:space-x-0">
        <div class="flex flex-col justify-start items-start flex-shrink-0">
          <div class="flex justify-center w-full md:justify-start items-center space-x-4 py-8 border-b border-gray-200">
            <div class="flex justify-start items-start flex-col space-y-2">
              <p class="text-base dark:text-white font-semibold leading-4 text-left text-gray-800">{{ customer.first_name }} {{ customer.last_name }}</p>
              <p class="text-base dark:text-white font-semibold leading-4 text-left text-gray-800">{{ customer.phone}}</p>
            </div>
          </div>

          <div class="flex justify-center text-gray-800 dark:text-white md:justify-start items-center space-x-4 py-4 border-b border-gray-200 w-full">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M19 5H5C3.89543 5 3 5.89543 3 7V17C3 18.1046 3.89543 19 5 19H19C20.1046 19 21 18.1046 21 17V7C21 5.89543 20.1046 5 19 5Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
              <path d="M3 7L12 13L21 7" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <p class="cursor-pointer text-sm leading-5 ">{{ customer.email}}</p>
          </div>
        </div>
        <div class="flex justify-between xl:h-full items-stretch w-full flex-col mt-6 md:mt-0">
          <div class="flex justify-center md:justify-start xl:flex-col flex-col md:space-x-6 lg:space-x-8 xl:space-x-0 space-y-4 xl:space-y-12 md:space-y-0 md:flex-row items-center md:items-start">
            <div class="flex justify-center md:justify-start items-center md:items-start flex-col space-y-4 xl:mt-8">
              <p class="text-base dark:text-white font-semibold leading-4 text-center md:text-left text-gray-800">Shipping Address</p>
              <p class="w-48 lg:w-full dark:text-gray-300 xl:w-48 text-center md:text-left text-sm leading-5 text-gray-600">{{ shippingAddress.address1 }}  {{ shippingAddress.address1 }}  {{ shippingAddress.city }} {{ shippingAddress.state }} {{ shippingAddress.zipcode }} {{ shippingAddress.country }}</p>
            </div>
            <div class="flex justify-center md:justify-start items-center md:items-start flex-col space-y-4">
              <p class="text-base dark:text-white font-semibold leading-4 text-center md:text-left text-gray-800">Billing Address</p>
              <p class="w-48 lg:w-full dark:text-gray-300 xl:w-48 text-center md:text-left text-sm leading-5 text-gray-600">{{ billingAddress.address1 }}  {{ billingAddress.address1 }}  {{ billingAddress.city }} {{ billingAddress.state }} {{ billingAddress.zipcode }} {{ billingAddress.country }}</p>
            </div>
          </div>
          <div class="flex w-full justify-center items-center md:justify-start md:items-start flex-col">
            <p class="text-base dark:text-white font-semibold leading-4 text-center md:text-left text-gray-800">Change Status</p>
                <select class="w-full h-10 appearance-none relative block w-24px px-3 py-2 my-4 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" v-model="order.status" @change="orderStatusChange()">
                    <option v-for="status of orderStatuses">{{ status }}</option>
                </select>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<script setup>
import {computed, onMounted, ref} from "vue";
import { useRoute } from "vue-router";
import store from '../../store';
import OrderStatus from "./OrderStatus.vue";
import axiosClient from "../../axios";

const route = useRoute();

const order = ref({});
const customer = ref({});
const shippingAddress = ref({});
const billingAddress = ref({});
const orderStatuses = ref([]);

onMounted(() => {
    store.dispatch('getOrder', route.params.id).then(({data}) => {
        order.value = data.data;
        customer.value = order.value.customer
        shippingAddress.value = customer.value.shippingAddress
        billingAddress.value = customer.value.billingAddress
        console.log(order.value);
    }).catch((err) => {
        // console.log(err);
    });

    store.dispatch('getStatuses').then(({data}) => {

        orderStatuses.value = data;
    });
})

function orderStatusChange() {
    axiosClient.post(`/orders/change-status/${order.value.id}/${order.value.status}`).then(({data}) => {
        console.log(data);
        store.commit('showToast', `Order status has successfully changed into "${order.value.status}"`)
    }) 
}

</script>