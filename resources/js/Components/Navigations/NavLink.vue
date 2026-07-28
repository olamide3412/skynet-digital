<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        default: '#'
    },
    active: {
        type: Boolean,
        default: false
    },
    onClick: Function
});
</script>

<template>
  <li class="list-none inline-block">
    <Link
      :href="href"
      class="inline-flex items-center py-2 px-3.5 text-sm font-semibold transition-all duration-200 rounded-xl"
      :class="[
        active 
          ? 'text-primary dark:text-secondary-200 font-bold bg-primary/10 dark:bg-secondary/20 border-b-2 border-primary dark:border-secondary-200' 
          : 'text-gray-700 dark:text-gray-200 hover:text-primary dark:hover:text-white hover:bg-gray-100/80 dark:hover:bg-gray-800/80'
      ]"
      @click="onClick"
    >
      <slot/>
    </Link>
  </li>
</template>
