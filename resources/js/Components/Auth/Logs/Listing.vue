<script setup>
import { computed } from 'vue';

const props = defineProps({
  log: {
    type: Object,
    required: true
  },
  index: {
    type: Number,
    required: true
  },
  currentPage: {
    type: Number,
    required: true
  },
  perPage: {
    type: Number,
    required: true
  }
});

const serial = computed(() => {
  return (props.currentPage - 1) * props.perPage + props.index + 1;
});
</script>

<template>
  <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-5 shadow-sm hover:shadow-md transition duration-200 space-y-3">
    
    <!-- Top Row: Serial #, User info & Timestamp -->
    <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-gray-100 dark:border-gray-700/60 text-xs">
      <div class="flex items-center space-x-2">
        <span class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold font-mono">
          #{{ serial }}
        </span>

        <!-- User Attribution Badge -->
        <span v-if="log.user" class="inline-flex items-center px-2.5 py-1 rounded-lg bg-purple-50 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300 font-semibold border border-purple-200 dark:border-purple-800">
          <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          {{ log.user.name || 'User #' + log.user_id }}
        </span>
        <span v-else-if="log.user_id" class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 font-semibold">
          User ID: {{ log.user_id }}
        </span>
        <span v-else class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400 font-medium">
          System Event
        </span>
      </div>

      <div class="text-xs text-gray-400 font-medium flex items-center">
        <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ $formatDate ? $formatDate(log.created_at, 'ddd, DD-MMM-YYYY HH:mm:ss') : new Date(log.created_at).toLocaleString() }}
      </div>
    </div>

    <!-- Log Content Body -->
    <div class="flex items-start space-x-3 pt-1">
      <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-300 flex items-center justify-center flex-shrink-0 mt-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 font-mono leading-relaxed bg-gray-50 dark:bg-gray-900/80 p-3 rounded-xl border border-gray-100 dark:border-gray-700/60 break-words">
          {{ log.log }}
        </p>
      </div>
    </div>

  </div>
</template>
