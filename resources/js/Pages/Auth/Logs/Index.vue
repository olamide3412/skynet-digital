<script setup>
import { reactive, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import LogListing from '@/Components/Auth/Logs/Listing.vue';
import Pagination from '@/Components/Pagination.vue';
import { debounce } from 'lodash';

const props = defineProps({
    logs: {
        type: Object,
        required: true
    },
    searchTerm: String,
    orderBy: {
        type: String,
        default: 'id'
    },
    orderDir: {
        type: String,
        default: 'desc'
    }
});

const params = reactive({
    search: props.searchTerm || '',
    orderBy: props.orderBy,
    orderDir: props.orderDir
});

watch(params,
    debounce((newParams) => {
        router.get(
            route('log.index'),
            { search: newParams.search, orderBy: newParams.orderBy, orderDir: newParams.orderDir },
            { preserveState: true, replace: true }
        );
    }, 500)
);
</script>

<template>
    <Head title="System Logs - Admin" />

    <div class="max-w-7xl mx-auto space-y-6 py-6 sm:px-6 lg:px-8">
        
        <!-- Header & Action Row -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200 dark:border-gray-800">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">System Activity Logs</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Audit trail, security events, and system activity records.</p>
            </div>

            <!-- Quick Metrics Badge -->
            <div class="flex items-center space-x-3">
                <div class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xs text-xs font-bold text-gray-700 dark:text-gray-300">
                    Total Audit Logs: <span class="text-primary dark:text-secondary-200 font-extrabold text-sm ml-1">{{ logs.total || logs.data?.length || 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Controls Toolbar: Search & Sort Selectors -->
        <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Live Search Input -->
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    v-model="params.search"
                    type="search"
                    placeholder="Search logs by keyword or content..."
                    class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-gray-900/90 border border-gray-300 dark:border-gray-700 rounded-xl text-xs text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-primary focus:border-transparent transition"
                />
                <button 
                    v-if="params.search" 
                    @click="params.search = ''" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                >
                    Clear
                </button>
            </div>

            <!-- Sorting Selectors -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center space-x-2">
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400">Sort By:</label>
                    <select 
                        v-model="params.orderBy"
                        class="px-3 py-2 bg-gray-50 dark:bg-gray-900/90 border border-gray-300 dark:border-gray-700 rounded-xl text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition font-medium"
                    >
                        <option value="id">ID / Sequence</option>
                        <option value="log">Log Content</option>
                        <option value="user_id">User ID</option>
                        <option value="created_at">Created Date</option>
                    </select>
                </div>

                <div class="flex items-center space-x-2">
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400">Order:</label>
                    <select 
                        v-model="params.orderDir"
                        class="px-3 py-2 bg-gray-50 dark:bg-gray-900/90 border border-gray-300 dark:border-gray-700 rounded-xl text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition font-medium"
                    >
                        <option value="desc">Newest First (DESC)</option>
                        <option value="asc">Oldest First (ASC)</option>
                    </select>
                </div>
            </div>

        </div>

        <!-- Log List Feed -->
        <div v-if="logs.data && logs.data.length > 0" class="space-y-4">
            <LogListing 
                v-for="(log, index) in logs.data" 
                :key="log.id"
                :log="log" 
                :index="index" 
                :currentPage="logs.current_page"
                :perPage="logs.per_page" 
            />

            <!-- Pagination -->
            <div class="pt-4 flex justify-center">
                <Pagination :paginator="logs" />
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-16 bg-white dark:bg-gray-800/90 rounded-2xl border border-gray-200 dark:border-gray-700/80 shadow-sm text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-base font-semibold text-gray-600 dark:text-gray-300">No activity logs recorded.</p>
            <p class="text-xs text-gray-400 mt-1">Try adjusting your search criteria or sort filters.</p>
        </div>

    </div>
</template>
