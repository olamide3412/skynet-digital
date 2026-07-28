<script setup>
const model = defineModel({
  type: null,
  required: true,
});

defineProps({
  name: {
    type: String,
    required: true,
  },
  options: {
    type: Array,
    required: true,
    // Expected format: [{ value: '1', label: 'Option 1' }, ...]
  },
  message: String,
  required: {
    type: Boolean,
    default: false,
  },

  disabled: {
    type: Boolean,
    default: false,
  },
});
</script>

<template>
  <div class="mb-2">
    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
      {{ name }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <select
      v-model="model"
      :required="required"
      :disabled="disabled"
      class="block w-full rounded-xl border border-gray-300 dark:border-gray-600 px-4 py-3 text-slate-900 dark:text-white shadow-xs focus:ring-2 focus:ring-primary bg-white dark:bg-gray-800 text-sm font-semibold cursor-pointer"
      :class="{ '!ring-red-500': message }"
    >
      <option value="" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-semibold">Select {{ name }}</option>
      <option
        v-for="(option, index) in options"
        :key="index"
        :value="option.value"
        class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-semibold"
      >
        {{ option.label }}
      </option>
    </select>
    <small class="error text-red-500 text-xs font-bold mt-1 block" v-if="message">{{ message }}</small>
  </div>
</template>
