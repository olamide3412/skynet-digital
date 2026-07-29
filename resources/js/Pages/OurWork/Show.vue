<script setup>
import Layout from '@/Layouts/Layout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useToast } from 'vue-toastification';
import CallToAction from '@/Components/Home/CallToAction.vue';
import Footer from '@/Components/Footer.vue';

const toast = useToast();

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
    meta: {
        type: Object,
        default: null,
    }
});

defineOptions({ layout: Layout });

// Lightbox state for gallery
const activeMedia = ref(null);
const isLightboxOpen = ref(false);

const openLightbox = (item) => {
    activeMedia.value = item;
    isLightboxOpen.value = true;
};

const closeLightbox = () => {
    isLightboxOpen.value = false;
    activeMedia.value = null;
};

const getMediaSrc = (m) => {
    if (m.source_type === 'file' && m.file_path) {
        return `/storage/${m.file_path}`;
    }
    return m.url;
};

const isYouTubeUrl = (url) => {
    return url && (url.includes('youtube.com') || url.includes('youtu.be'));
};

const getYouTubeEmbedUrl = (url) => {
    if (!url) return '';
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    const match = url.match(regExp);
    return (match && match[2].length === 11) ? `https://www.youtube.com/embed/${match[2]}?autoplay=1` : url;
};

// Rich Markdown & Formatting Parser for Description
const formattedDescription = computed(() => {
    if (!props.project.description) {
        return '<p class="text-gray-400 italic">No detailed description provided.</p>';
    }

    let text = props.project.description;

    // Process Headings
    text = text.replace(/^### (.*$)/gim, '<h3 class="text-lg font-extrabold text-gray-900 dark:text-white mt-5 mb-2">$1</h3>');
    text = text.replace(/^## (.*$)/gim, '<h2 class="text-xl font-black text-gray-900 dark:text-white mt-6 mb-3">$1</h2>');
    text = text.replace(/^# (.*$)/gim, '<h1 class="text-2xl font-black text-gray-900 dark:text-white mt-6 mb-3">$1</h1>');

    // Blockquotes
    text = text.replace(/^> (.*$)/gim, '<blockquote class="border-l-4 border-primary dark:border-secondary-200 pl-4 py-2 my-3 italic text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800/60 rounded-r-xl">$1</blockquote>');

    // Bullet lists
    text = text.replace(/^\s*[-*]\s+(.*$)/gim, '<li class="ml-5 list-disc text-gray-700 dark:text-gray-300 my-1">$1</li>');

    // Bold (**text**)
    text = text.replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold text-gray-900 dark:text-white">$1</strong>');

    // Italic (*text*)
    text = text.replace(/\*(.*?)\*/g, '<em class="italic">$1</em>');

    // Strikethrough (~~text~~)
    text = text.replace(/~~(.*?)~~/g, '<del class="line-through text-gray-400">$1</del>');

    // Links [text](url)
    text = text.replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" target="_blank" rel="noopener noreferrer" class="text-primary dark:text-secondary-200 font-semibold hover:underline">$1</a>');

    // Process paragraphs and lists wrapper
    const lines = text.split(/\r?\n/);
    let inList = false;
    let html = '';

    lines.forEach(line => {
        const trimmed = line.trim();
        if (trimmed.startsWith('<li')) {
            if (!inList) {
                html += '<ul class="my-3 space-y-1.5">';
                inList = true;
            }
            html += trimmed;
        } else {
            if (inList) {
                html += '</ul>';
                inList = false;
            }
            if (trimmed.startsWith('<h') || trimmed.startsWith('<blockquote')) {
                html += trimmed;
            } else if (trimmed === '') {
                html += '<div class="h-2"></div>';
            } else {
                html += `<p class="mb-2 leading-relaxed text-gray-700 dark:text-gray-300">${trimmed}</p>`;
            }
        }
    });

    if (inList) html += '</ul>';

    return html;
});

const seoTitle = computed(() => {
    if (props.project.meta_title) return props.project.meta_title;
    return `${props.project.title} | Skynet Digital Limited`;
});

const seoDescription = computed(() => {
    if (props.project.meta_description) return props.project.meta_description;
    if (props.project.description) {
        const cleanText = props.project.description.replace(/<[^>]*>?/gm, '').trim();
        return cleanText.substring(0, 160) + (cleanText.length > 160 ? '...' : '');
    }
    return `Details about ${props.project.title} by Skynet Digital Limited.`;
});

const seoKeywords = computed(() => {
    if (props.project.meta_keywords) return props.project.meta_keywords;
    return [props.project.category, props.project.service, props.project.industry, 'Skynet Digital Limited', 'Case Study'].filter(Boolean).join(', ');
});

const seoImage = computed(() => {
    if (props.project.image_path) {
        if (typeof window !== 'undefined') {
            return `${window.location.origin}/storage/${props.project.image_path}`;
        }
    }
    return null;
});

const seoUrl = computed(() => {
    if (typeof window !== 'undefined') {
        return window.location.href;
    }
    return '';
});

const copyToClipboard = () => {
    if (typeof navigator !== 'undefined' && navigator.clipboard) {
        navigator.clipboard.writeText(seoUrl.value)
            .then(() => toast.success('Link copied to clipboard!'))
            .catch(() => toast.error('Failed to copy link.'));
    }
};
</script>

<template>
    <Head>
        <title>{{ seoTitle }}</title>
        <meta head-key="description" name="description" :content="seoDescription" />
        <meta head-key="keywords" name="keywords" :content="seoKeywords" />
        
        <!-- Open Graph / Facebook / WhatsApp -->
        <meta head-key="og:title" property="og:title" :content="seoTitle" />
        <meta head-key="og:description" property="og:description" :content="seoDescription" />
        <meta head-key="og:type" property="og:type" content="article" />
        <meta head-key="og:url" property="og:url" :content="seoUrl" />
        <meta head-key="og:site_name" property="og:site_name" content="Skynet Digital Limited" />
        <meta v-if="seoImage" head-key="og:image" property="og:image" :content="seoImage" />
        <meta v-if="seoImage" head-key="og:image:secure_url" property="og:image:secure_url" :content="seoImage" />
        <meta v-if="seoImage" head-key="og:image:type" property="og:image:type" content="image/jpeg" />
        <meta v-if="seoImage" head-key="og:image:width" property="og:image:width" content="1200" />
        <meta v-if="seoImage" head-key="og:image:height" property="og:image:height" content="630" />
        
        <!-- Twitter / X -->
        <meta head-key="twitter:card" name="twitter:card" content="summary_large_image" />
        <meta head-key="twitter:title" name="twitter:title" :content="seoTitle" />
        <meta head-key="twitter:description" name="twitter:description" :content="seoDescription" />
        <meta v-if="seoImage" head-key="twitter:image" name="twitter:image" :content="seoImage" />
    </Head>

    <div class="relative bg-gray-50 dark:bg-gray-900 min-h-screen pt-20 pb-24 px-4 sm:px-6 lg:px-8">
        
        <!-- Background Ambient Glow -->
        <div class="absolute inset-x-0 top-0 h-96 bg-gradient-to-b from-primary/10 via-purple-500/5 to-transparent pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto space-y-8">
            
            <!-- Breadcrumb Navigation -->
            <div>
                <Link 
                    :href="route('our-work')" 
                    class="inline-flex items-center text-xs font-bold text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-secondary-200 transition-colors"
                >
                    <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Our Work
                </Link>
            </div>

            <!-- Header Row -->
            <div class="space-y-3 max-w-4xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 rounded-full bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200 text-xs font-bold uppercase tracking-wider">
                        {{ project.service || 'Web Solution' }}
                    </span>
                    <span v-if="project.category" class="px-3 py-1 rounded-full bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-300 text-xs font-bold uppercase tracking-wider">
                        📁 {{ project.category }}
                    </span>
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-tight">
                    {{ project.title }}
                </h1>
            </div>

            <!-- Main Layout Grid (2 Columns: 2/3 Left, 1/3 Right) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Main Column (2 Cols) -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Cover Image Banner -->
                    <div class="relative rounded-3xl overflow-hidden shadow-xl bg-gray-900 h-72 sm:h-96 border border-gray-200 dark:border-gray-800">
                        <img 
                            v-if="project.image_path" 
                            :src="'/storage/' + project.image_path" 
                            :alt="project.title" 
                            class="w-full h-full object-cover" 
                        />
                        <div v-else class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gradient-to-br from-gray-800 to-gray-900">
                            <svg class="w-16 h-16 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-xs font-semibold">Skynet Digital Project Showcase</span>
                        </div>
                    </div>

                    <!-- Project Description Card with Rich Formatting Support -->
                    <div class="bg-white dark:bg-gray-800/90 border border-gray-200/80 dark:border-gray-700/80 rounded-3xl p-6 sm:p-10 shadow-sm space-y-6">
                        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight pb-3 border-b border-gray-100 dark:border-gray-700">
                            Project Overview & Specifications
                        </h2>

                        <!-- Formatted HTML Description Container -->
                        <div class="prose prose-lg dark:prose-invert max-w-none text-gray-700 dark:text-gray-200">
                            <div v-html="formattedDescription" class="leading-relaxed text-sm sm:text-base"></div>
                        </div>
                    </div>

                    <!-- Multi-Media Gallery Showcase -->
                    <div v-if="project.media && project.media.length > 0" class="bg-white dark:bg-gray-800/90 border border-gray-200/80 dark:border-gray-700/80 rounded-3xl p-6 sm:p-10 shadow-sm space-y-6">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
                                <span>Gallery & Media Demos</span>
                            </h3>
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200">
                                {{ project.media.length }} Items
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <div 
                                v-for="(item, idx) in project.media" 
                                :key="item.id"
                                @click="openLightbox(item)"
                                class="relative group rounded-2xl overflow-hidden bg-gray-900 border border-gray-200 dark:border-gray-800 h-44 cursor-pointer shadow-sm hover:shadow-lg transition duration-300 transform hover:-translate-y-1"
                            >
                                <!-- Image Media -->
                                <template v-if="item.type === 'image'">
                                    <img :src="getMediaSrc(item)" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" :alt="project.title + ' media ' + idx" />
                                </template>

                                <!-- Video Media -->
                                <template v-else>
                                    <video v-if="item.source_type === 'file'" :src="getMediaSrc(item)" class="w-full h-full object-cover"></video>
                                    <div v-else class="w-full h-full flex flex-col items-center justify-center p-3 text-center text-white bg-gradient-to-br from-indigo-900 to-purple-950">
                                        <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center mb-2 backdrop-blur-xs group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-semibold text-white/90">Watch Video Demo</span>
                                    </div>
                                </template>

                                <!-- Type Badge Overlay -->
                                <div class="absolute top-2.5 left-2.5">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold text-white uppercase bg-black/60 backdrop-blur-xs shadow-xs">
                                        {{ item.type === 'video' ? '🎬 Video' : '🖼️ Photo' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Sidebar Column (1 Col) -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Card 1: Project Metadata & Live Demo -->
                    <div class="bg-white dark:bg-gray-800/90 border border-gray-200/80 dark:border-gray-700/80 rounded-3xl p-6 shadow-sm space-y-6">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-gray-200 uppercase tracking-wider pb-3 border-b border-gray-100 dark:border-gray-700">
                            Project Metadata
                        </h3>

                        <div class="space-y-4 text-xs">
                            <div>
                                <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">Client Industry</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white mt-0.5 block">{{ project.industry || 'General Industry' }}</span>
                            </div>

                            <div>
                                <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">Service Delivered</span>
                                <span class="text-sm font-bold text-primary dark:text-secondary-200 mt-0.5 block">{{ project.service || 'Web Solution' }}</span>
                            </div>

                            <div v-if="project.category">
                                <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">Category</span>
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300 mt-0.5 block">📁 {{ project.category }}</span>
                            </div>
                        </div>

                        <!-- Live Demo Button -->
                        <div v-if="project.demo_url" class="pt-2">
                            <a 
                                :href="project.demo_url" 
                                target="_blank" 
                                rel="noopener noreferrer"
                                class="w-full py-3.5 px-4 bg-primary hover:bg-primary-dark text-white font-bold rounded-2xl shadow-md hover:shadow-lg transition flex items-center justify-center text-sm"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Visit Live Demo ↗
                            </a>
                        </div>
                    </div>

                    <!-- Card 2: Share Project Box -->
                    <div class="bg-white dark:bg-gray-800/90 border border-gray-200/80 dark:border-gray-700/80 rounded-3xl p-6 shadow-sm space-y-4">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-gray-200 uppercase tracking-wider pb-2 border-b border-gray-100 dark:border-gray-700">
                            Share Project
                        </h3>

                        <div class="flex items-center gap-3">
                            <a :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(seoUrl)}`" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center rounded-xl bg-[#1877F2] text-white hover:opacity-90 transition shadow-xs" title="Share on Facebook">
                                <i class="fab fa-facebook-f text-sm"></i>
                            </a>
                            <a :href="`https://twitter.com/intent/tweet?url=${encodeURIComponent(seoUrl)}&text=${encodeURIComponent(seoTitle)}`" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center rounded-xl bg-[#1DA1F2] text-white hover:opacity-90 transition shadow-xs" title="Share on Twitter">
                                <i class="fab fa-twitter text-sm"></i>
                            </a>
                            <a :href="`https://www.linkedin.com/shareArticle?mini=true&url=${encodeURIComponent(seoUrl)}&title=${encodeURIComponent(seoTitle)}`" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center rounded-xl bg-[#0A66C2] text-white hover:opacity-90 transition shadow-xs" title="Share on LinkedIn">
                                <i class="fab fa-linkedin-in text-sm"></i>
                            </a>
                            <a :href="`https://api.whatsapp.com/send?text=${encodeURIComponent(seoTitle + ' ' + seoUrl)}`" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center rounded-xl bg-[#25D366] text-white hover:opacity-90 transition shadow-xs" title="Share on WhatsApp">
                                <i class="fab fa-whatsapp text-sm"></i>
                            </a>
                            <button @click="copyToClipboard" class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-700 text-white hover:opacity-90 transition shadow-xs" title="Copy Link">
                                <i class="fas fa-link text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Card 3: Consultation CTA Box -->
                    <div class="bg-gradient-to-br from-primary via-indigo-700 to-purple-800 dark:from-gray-800 dark:to-gray-900 rounded-3xl p-6 text-white shadow-lg space-y-4 border border-primary/20 dark:border-gray-700">
                        <span class="text-[10px] uppercase font-bold tracking-widest px-2.5 py-1 bg-white/20 rounded-full">Need A Similar Solution?</span>
                        <h4 class="text-lg font-extrabold leading-snug">Let us build your next digital product.</h4>
                        <p class="text-xs text-gray-100 dark:text-gray-300 leading-relaxed">
                            Our team engineers custom web apps, SaaS platforms, and secure infrastructure tailored to your business goals.
                        </p>
                        <Link 
                            :href="route('contact')"
                            class="inline-block w-full py-3 text-center bg-white text-primary dark:bg-secondary-200 dark:text-gray-900 font-bold text-xs rounded-xl shadow hover:bg-gray-50 transition"
                        >
                            Get In Touch With Us →
                        </Link>
                    </div>

                </div>

            </div>
        </div>

        <!-- Lightbox Modal Player -->
        <div 
            v-if="isLightboxOpen && activeMedia"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-sm transition-opacity"
            @click.self="closeLightbox"
        >
            <div class="relative max-w-5xl w-full max-h-[90vh] overflow-hidden rounded-2xl flex flex-col items-center justify-center">
                <button 
                    @click="closeLightbox"
                    class="absolute top-3 right-3 z-50 p-2 rounded-full bg-black/60 text-white hover:bg-black transition"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Lightbox Content -->
                <div class="w-full h-full flex items-center justify-center">
                    <template v-if="activeMedia.type === 'image'">
                        <img :src="getMediaSrc(activeMedia)" class="max-h-[85vh] max-w-full object-contain rounded-xl shadow-2xl" :alt="project.title" />
                    </template>

                    <template v-else>
                        <video v-if="activeMedia.source_type === 'file'" :src="getMediaSrc(activeMedia)" controls autoplay class="max-h-[85vh] max-w-full rounded-xl shadow-2xl"></video>
                        <iframe v-else-if="isYouTubeUrl(activeMedia.url)" :src="getYouTubeEmbedUrl(activeMedia.url)" class="w-full h-[70vh] rounded-xl shadow-2xl" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <video v-else :src="activeMedia.url" controls autoplay class="max-h-[85vh] max-w-full rounded-xl shadow-2xl"></video>
                    </template>
                </div>
            </div>
        </div>

    </div>
    
    <CallToAction />
    <Footer/>
</template>
