<template>
    <GuestLayout title="Set New Password">
        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" action="#" method="POST">
                <div>
                    <label for="new-password" class="block text-sm font-medium leading-6 text-gray-900">New Password</label>
                    <div class="mt-2">
                        <input id="new-password" name="pnew-password" type="password" required placeholder="New Password"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="password-repeat" class="block text-sm font-medium leading-6 text-gray-900">Repeat
                        Password</label>
                    <div class="mt-2">
                        <input id="password-repeat" name="password-repeat" type="password" required
                            placeholder="Repeat Password"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <div class="text-sm">
                            <router-link :to="{ name: 'login' }"
                                class="font-semibold text-indigo-600 hover:text-indigo-500">Go back to Login</router-link>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <LockClosedIcon class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400"
                                aria-hidden="true" />
                        </span>
                        Submit</button>
                </div>
            </form>


        </div>
    </GuestLayout>
</template>
<script setup>
import { LockClosedIcon } from '@heroicons/vue/solid';
</script>