<script setup>
import { Link } from '@inertiajs/vue3';

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
                <div v-for="project in projects" :key="project.id" class="flex flex-col rounded-lg shadow-lg overflow-hidden bg-white dark:bg-gray-700 group">
                    <div class="flex-shrink-0">
                        <img v-if="project.image_path" class="h-48 w-full object-cover" :src="'/storage/' + project.image_path" :alt="project.title">
                        <div v-else class="h-48 w-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center">
                            <span class="text-gray-500 dark:text-gray-400">No Image</span>
                        </div>
                    </div>
                    <div class="flex-1 p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class=" hidden text-xs font-bold text-primary dark:text-secondary-200 uppercase tracking-wider mb-2">
                                {{ project.service }}
                            </p>
                            <div class="block">
                                <p class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-primary dark:group-hover:text-secondary-200 transition-colors">
                                    {{ project.title }}
                                </p>
                                <p class="mt-3 text-base text-gray-500 dark:text-gray-300 line-clamp-3">
                                    {{ project.service }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-between border-t border-gray-100 dark:border-gray-600 pt-4">
                             <div>
                                <p class="text-xs font-medium text-gray-400 dark:text-gray-400 uppercase tracking-wider">
                                    Industry
                                </p>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    {{ project.industry || 'General' }}
                                </p>
                             </div>
                             
                             <Link :href="route('portfolio.show', project.id)" class="inline-flex items-center text-sm font-semibold text-primary dark:text-secondary-200 hover:underline group-hover:underline">
                                Read more
                                <svg class="ml-1 w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                             </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
