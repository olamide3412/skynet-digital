<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <section class="py-16 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 transition-colors">
        <div class="max-w-7xl mx-auto">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary via-indigo-600 to-purple-700 dark:from-gray-800 dark:to-gray-900 border border-primary/20 dark:border-gray-700/80 p-8 sm:p-12 lg:p-16 text-white shadow-2xl">
                
                <!-- Decorative Blur Circles -->
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 lg:flex lg:items-center lg:justify-between gap-8 space-y-6 lg:space-y-0">
                    <div class="max-w-2xl space-y-3">
                        <span class="inline-block px-3.5 py-1 bg-white/20 dark:bg-gray-700/60 backdrop-blur-md rounded-full text-xs font-bold uppercase tracking-wider">
                            🚀 Ready to Accelerate Your Business?
                        </span>
                        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
                            Let us build, secure, and scale your digital systems.
                        </h2>
                        <p class="text-sm sm:text-base text-gray-100 dark:text-gray-300 leading-relaxed font-normal">
                            Partner with Skynet Digital Limited for custom software development, enterprise networking, and cloud infrastructure tailored to your strategic goals.
                        </p>
                    </div>

                    <div class="flex-shrink-0 flex flex-col sm:flex-row items-center gap-4">
                        <Link 
                            :href="route('contact')" 
                            class="w-full sm:w-auto px-8 py-4 bg-white text-primary dark:bg-secondary-200 dark:text-gray-900 font-extrabold text-sm sm:text-base rounded-2xl shadow-lg hover:bg-gray-50 dark:hover:bg-white transition transform active:scale-95 flex items-center justify-center"
                        >
                            <span>Schedule Free Consultation</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </Link>
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>
