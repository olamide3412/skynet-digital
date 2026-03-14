<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    messages: Array,
});
</script>

<template>
    <Head title="Contact Messages" />

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">
            Contact Messages
        </h2>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg rounded-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="p-4 font-semibold text-gray-700 dark:text-gray-200">Date</th>
                            <th class="p-4 font-semibold text-gray-700 dark:text-gray-200">Name</th>
                            <th class="p-4 font-semibold text-gray-700 dark:text-gray-200">Email</th>
                            <th class="p-4 font-semibold text-gray-700 dark:text-gray-200">Service</th>
                            <th class="p-4 font-semibold text-gray-700 dark:text-gray-200">Message</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        <tr v-for="message in messages" :key="message.id" class="hover:bg-gray-50 dark:hover:bg-gray-900 transition font-medium">
                            <td class="p-4 text-gray-600 dark:text-gray-400 whitespace-nowrap">
                                {{ new Date(message.created_at).toLocaleDateString() }}
                            </td>
                            <td class="p-4 text-gray-900 dark:text-white">
                                {{ message.name }}
                            </td>
                            <td class="p-4 text-gray-600 dark:text-gray-400">
                                {{ message.email }} <br>
                                <span class="text-xs">{{ message.phone }}</span>
                            </td>
                            <td class="p-4 text-gray-600 dark:text-gray-400 whitespace-nowrap">
                                {{ message.service }}
                            </td>
                            <td class="p-4 text-gray-600 dark:text-gray-300 max-w-xs truncate" :title="message.message">
                                {{ message.message }}
                            </td>
                        </tr>
                        <tr v-if="messages.length === 0">
                            <td colspan="5" class="p-8 text-center text-gray-500">
                                No messages found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
