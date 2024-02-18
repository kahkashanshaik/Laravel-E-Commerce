<template>
    <div>
        <div class="grid grid-cols-3 gap-3">
            <div class="col-span-2 grid grid-cols-2 gap-3">
                <router-link :to="{name: 'reports.orders', params: route.params}"
                     class="bg-white py-2 px-3 text-gray-700 rounded-md text-center"
                     active-class="text-indigo-600 bg-indigo-50">Orders Report
        </router-link>
        <router-link :to="{name: 'reports.customers', params: route.params}"
                     class="bg-white py-2 px-3 text-gray-700 rounded-md text-center"
                     active-class="text-indigo-600 bg-indigo-50">Customers Report
        </router-link>
            </div>
            <div>
                <CustomInput type="select" v-model="choosenDate" @change="onDatePickerChange()" :select-options="dateOptions"/>
            </div>
        </div>
        <div class="bg-white p-3 rounded-md mt-3 shadow-md">
            <router-view/>
        </div>
    </div>
</template>
<script setup>
import { onMounted, computed, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import store from '../../store';
import CustomInput from '../../components/core/CustomInput.vue';
const router = useRouter();
const route = useRoute();
const dateOptions = computed(() => store.state.dateOptions);
const choosenDate = ref('all');

function onDatePickerChange() {
    router.push({name: route.name, params: {date: choosenDate.value}});
}
</script>