<script setup>
import Layout from '@/Layouts/Layout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import CallToAction from '@/Components/Home/CallToAction.vue';
import Footer from '@/Components/Footer.vue';

const props = defineProps({
    project: {
        type: Object,
        required: true,
    }
});

defineOptions({ layout: Layout })

const seoTitle = computed(() => `${props.project.title} - Portfolio | Skynet Digital Limited`);
const seoDescription = computed(() => {
    if (props.project.description) {
        return props.project.description.substring(0, 160).trim() + '...';
    }
    return `Details about ${props.project.title} by Skynet Digital Limited.`;
});
const seoImage = computed(() => {
    if (typeof window !== 'undefined' && props.project.image_path) {
        return `${window.location.origin}/storage/${props.project.image_path}`;
    }
    return null; // fallback will be used by the crawler
});
const seoUrl = computed(() => {
    if (typeof window !== 'undefined') {
        return window.location.href;
    }
    return '';
});
</script>

<template>
    <Head>
        <title>{{ seoTitle }}</title>
        <meta head-key="description" name="description" :content="seoDescription" />
        
        <!-- Open Graph / Facebook -->
        <meta head-key="og:title" property="og:title" :content="seoTitle" />
        <meta head-key="og:description" property="og:description" :content="seoDescription" />
        <meta head-key="og:type" property="og:type" content="article" />
        <meta v-if="seoImage" head-key="og:image" property="og:image" :content="seoImage" />
        <meta v-if="seoImage" head-key="og:image:width" property="og:image:width" content="1200" />
        <meta v-if="seoImage" head-key="og:image:height" property="og:image:height" content="630" />
        
        <!-- Twitter -->
        <meta head-key="twitter:title" name="twitter:title" :content="seoTitle" />
        <meta head-key="twitter:description" name="twitter:description" :content="seoDescription" />
        <meta v-if="seoImage" head-key="twitter:image" name="twitter:image" :content="seoImage" />
    </Head>

    <div class="relative bg-gray-50 dark:bg-gray-800 pt-16 pb-20 px-4 sm:px-6 lg:pt-24 lg:pb-28 lg:px-8 min-h-screen">
        <div class="max-w-4xl mx-auto">
            
            <!-- Breadcrumb / Back Link -->
            <div class="mb-8">
                <Link href="/portfolio" class="inline-flex items-center text-sm font-medium text-primary hover:text-primary-dark dark:text-secondary-200 dark:hover:text-white transition-colors">
                    <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Portfolio
                </Link>
            </div>

            <!-- Project Card -->
            <div class="bg-white dark:bg-gray-900 shadow-xl rounded-2xl overflow-hidden flex flex-col">
                
                <!-- Project Image -->
                <div class="w-full h-64 sm:h-96 relative bg-gray-200 dark:bg-gray-700">
                    <img v-if="project.image_path" :src="'/storage/' + project.image_path" :alt="project.title" class="object-cover w-full h-full" />
                    <div v-else class="flex flex-col items-center justify-center w-full h-full text-gray-400 dark:text-gray-500">
                        <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>No Image Available</span>
                    </div>
                </div>

                <!-- Project Info -->
                <div class="p-8 sm:p-12">
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                        <div>
                            <span class="inline-block px-3 py-1 rounded-full bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200 text-xs font-semibold uppercase tracking-wider mb-2">
                                {{ project.service || 'Service' }}
                            </span>
                            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-2">
                                {{ project.title }}
                            </h1>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Industry: <span class="text-gray-900 dark:text-gray-200">{{ project.industry || 'General' }}</span>
                            </p>
                        </div>
                        
                        <a v-if="project.demo_url" :href="project.demo_url" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary hover:bg-primary-dark transition shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Visit Live Project
                        </a>
                    </div>

                    <div class="prose prose-lg dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                        <div style="white-space: pre-wrap;">{{ project.description || 'No description provided.' }}</div>
                    </div>
                    
                    <!-- Share Buttons -->
                    <div class="mt-12 pt-8 border-t border-gray-100 dark:border-gray-800">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Share this project</h3>
                        <div class="flex items-center gap-4">
                            <a :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(seoUrl)}`" target="_blank" rel="noopener noreferrer" class="w-10 h-10 flex items-center justify-center rounded-full bg-[#1877F2] text-white hover:opacity-90 transition-opacity shadow-sm" aria-label="Share on Facebook">
                                <i class="fab fa-facebook-f text-lg"></i>
                            </a>
                            <a :href="`https://twitter.com/intent/tweet?url=${encodeURIComponent(seoUrl)}&text=${encodeURIComponent(seoTitle)}`" target="_blank" rel="noopener noreferrer" class="w-10 h-10 flex items-center justify-center rounded-full bg-[#1DA1F2] text-white hover:opacity-90 transition-opacity shadow-sm" aria-label="Share on Twitter">
                                <i class="fab fa-twitter text-lg"></i>
                            </a>
                            <a :href="`https://www.linkedin.com/shareArticle?mini=true&url=${encodeURIComponent(seoUrl)}&title=${encodeURIComponent(seoTitle)}`" target="_blank" rel="noopener noreferrer" class="w-10 h-10 flex items-center justify-center rounded-full bg-[#0A66C2] text-white hover:opacity-90 transition-opacity shadow-sm" aria-label="Share on LinkedIn">
                                <i class="fab fa-linkedin-in text-lg"></i>
                            </a>
                            <a :href="`https://api.whatsapp.com/send?text=${encodeURIComponent(seoTitle + ' ' + seoUrl)}`" target="_blank" rel="noopener noreferrer" class="w-10 h-10 flex items-center justify-center rounded-full bg-[#25D366] text-white hover:opacity-90 transition-opacity shadow-sm" aria-label="Share on WhatsApp">
                                <i class="fab fa-whatsapp text-lg"></i>
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
    <CallToAction />
    <Footer/>
</template>
