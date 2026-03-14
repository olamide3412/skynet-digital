<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <section class="bg-primary dark:bg-primary-dark">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                <span class="block">Ready to get started?</span>
                <span class="block text-indigo-200">Let us help you build, secure, and grow.</span>
            </h2>
            <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                <div class="inline-flex rounded-md shadow">
                    <Link :href="route('contact')" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-primary bg-white hover:bg-gray-50">
                        Request a Free Consultation
                    </Link>
                </div>
            </div>
        </div>
    </section>
</template>
