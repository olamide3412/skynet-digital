<script setup>
defineProps({
    projects: {
        type: Array,
        required: true,
    }
});
</script>

<template>
    <div class="relative bg-gray-50 dark:bg-gray-800 pt-16 pb-20 px-4 sm:px-6 lg:pt-24 lg:pb-28 lg:px-8">
        <div class="absolute inset-0">
            <div class="bg-white dark:bg-gray-900 h-1/3 sm:h-2/3"></div>
        </div>
        <div class="relative max-w-7xl mx-auto">
            <div class="text-center">
                <h2 class="text-3xl tracking-tight font-extrabold text-gray-900 dark:text-white sm:text-4xl">
                    Our Work
                </h2>
                <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 dark:text-gray-300 sm:mt-4">
                    Here are some of the projects and systems we have successfully delivered for our clients across different industries.
                </p>
            </div>

            <div v-if="projects.length === 0" class="mt-12 text-center text-gray-500 dark:text-gray-400">
                <p class="text-lg">Projects will be added soon. Stay tuned!</p>
            </div>

            <div v-else class="mt-12 max-w-lg mx-auto grid gap-5 lg:grid-cols-3 lg:max-w-none">
                <div v-for="project in projects" :key="project.id" class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                    <div class="flex-shrink-0">
                        <img v-if="project.image_path" class="h-48 w-full object-cover" :src="'/storage/' + project.image_path" :alt="project.title">
                        <div v-else class="h-48 w-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center">
                            <span class="text-gray-500 dark:text-gray-400">No Image</span>
                        </div>
                    </div>
                    <div class="flex-1 bg-white dark:bg-gray-700 p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-primary dark:text-secondary-200">
                                <span class="uppercase tracking-wider">
                                    {{ project.service }}
                                </span>
                            </p>
                            <div class="block mt-2">
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ project.title }}
                                </p>
                                <p class="mt-3 text-base text-gray-500 dark:text-gray-300 line-clamp-3">
                                    {{ project.description }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-between">
                             <div class="ml-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    Industry: <span class="text-gray-500 dark:text-gray-300">{{ project.industry || 'General' }}</span>
                                </p>
                             </div>
                             <a v-if="project.demo_url" :href="project.demo_url" target="_blank" rel="noopener noreferrer"
                                class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-md hover:bg-primary-dark transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                View Demo
                             </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
