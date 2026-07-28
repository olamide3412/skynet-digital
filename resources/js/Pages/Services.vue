<script setup>
import Layout from '@/Layouts/Layout.vue';
import { Head } from '@inertiajs/vue3';
import ServiceList from '@/Components/Services/ServiceList.vue';
import CallToAction from '@/Components/Home/CallToAction.vue';
import Footer from '@/Components/Footer.vue';

defineOptions({ layout: Layout });
</script>

<template>
    <Head>
        <title>Our Services - Skynet Digital Limited</title>
        <meta name="description" content="Explore our wide range of technology services including Networking, Product Development, Graphic Design, IT Training, and Maintenance." />
    </Head>

    <div class="relative bg-gray-50 dark:bg-gray-900 pt-20 pb-12 transition-colors border-b border-gray-200/60 dark:border-gray-800">
        <!-- Ambient Glow -->
        <div class="absolute inset-x-0 top-0 h-64 bg-gradient-to-b from-primary/10 via-purple-500/5 to-transparent pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <span class="inline-block px-4 py-1.5 rounded-full bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200 text-xs font-bold uppercase tracking-widest border border-primary/20 dark:border-secondary/30">
                ⚡ World-Class Technology Capabilities
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-tight">
                Our Services & Engineering Solutions
            </h1>
            <p class="max-w-2xl mx-auto text-base sm:text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                Discover how our end-to-end technology solutions empower businesses to build faster, protect critical infrastructure, and achieve strategic growth.
            </p>
        </div>
    </div>

    <ServiceList />
    <CallToAction />
    <Footer/>
</template>
