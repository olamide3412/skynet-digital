<script setup>
import Layout from '@/Layouts/Layout.vue';
import { Head } from '@inertiajs/vue3';
import CallToAction from '@/Components/Home/CallToAction.vue';
import MeetOurTeam from '@/Components/Home/MeetOurTeam.vue';
import Footer from '@/Components/Footer.vue';

defineOptions({ layout: Layout });

const values = [
    {
        title: 'Integrity',
        description: 'We are honest, transparent, and committed to delivering doing what is genuinely best for our clients.',
        iconPath: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'
    },
    {
        title: 'Innovation',
        description: 'We constantly explore emerging technologies to deliver modern, efficient, and future-proof systems.',
        iconPath: 'M13 10V3L4 14h7v7l9-11h-7z'
    },
    {
        title: 'Reliability',
        description: 'We build high-availability software and network architectures you can depend on, day in and day out.',
        iconPath: 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'
    },
    {
        title: 'Customer Success',
        description: 'Your growth is our priority. We succeed when your organization operates faster and achieves its goals.',
        iconPath: 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'
    }
];
</script>

<template>
    <Head>
        <title>About Us - Skynet Digital Limited</title>
        <meta name="description" content="Learn more about Skynet Digital Limited, our mission, vision, and the passionate team innovating for the future of technology in Africa." />
    </Head>

    <div class="relative bg-gray-50 dark:bg-gray-900 min-h-screen pt-20 pb-16 transition-colors">
        
        <!-- Ambient Background Blur -->
        <div class="absolute inset-x-0 top-0 h-96 bg-gradient-to-b from-primary/10 via-purple-500/5 to-transparent pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            
            <!-- Hero Header Section -->
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="inline-block px-4 py-1.5 rounded-full bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200 text-xs font-bold uppercase tracking-widest border border-primary/20 dark:border-secondary/30">
                    ⚡ Who We Are & What We Believe
                </span>
                <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-tight">
                    Innovating for the Future of Technology
                </h1>
                <p class="text-base sm:text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                    We are a premier technology service provider dedicated to helping enterprise organizations, SMEs, and individuals adopt, manage, and scale with modern digital solutions.
                </p>

                <!-- CAC Verification Badge -->
                <div class="pt-2 flex justify-center">
                    <span class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20 text-xs font-bold">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>CAC Registered Corporation • RC No. 9168845</span>
                    </span>
                </div>
            </div>

            <!-- Mission & Vision 2-Column Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Mission Card -->
                <div class="bg-white dark:bg-gray-800/90 border border-gray-200/80 dark:border-gray-700/80 rounded-3xl p-8 sm:p-10 shadow-sm hover:shadow-md transition space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-primary to-indigo-600 text-white flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white">Our Mission</h3>
                    <p class="text-base text-gray-600 dark:text-gray-300 leading-relaxed">
                        To simplify technology for businesses and empower people through practical, high-performance digital solutions, software engineering, and corporate IT training.
                    </p>
                </div>

                <!-- Vision Card -->
                <div class="bg-white dark:bg-gray-800/90 border border-gray-200/80 dark:border-gray-700/80 rounded-3xl p-8 sm:p-10 shadow-sm hover:shadow-md transition space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-purple-600 to-pink-600 text-white flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white">Our Vision</h3>
                    <p class="text-base text-gray-600 dark:text-gray-300 leading-relaxed">
                        To become the most trusted technology partner for enterprise organizations across Africa and beyond, renowned for technical excellence and client satisfaction.
                    </p>
                </div>

            </div>

            <!-- Core Values Grid -->
            <div class="space-y-8 pt-4">
                <div class="text-center max-w-2xl mx-auto">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        Our Core Values
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">The principles that guide our engineering standards and client relationships.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div 
                        v-for="val in values" 
                        :key="val.title"
                        class="bg-white dark:bg-gray-800/90 border border-gray-200/80 dark:border-gray-700/80 rounded-2xl p-6 shadow-xs hover:shadow-md transition space-y-3 group"
                    >
                        <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="val.iconPath" />
                            </svg>
                        </div>
                        <h4 class="text-base font-bold text-gray-900 dark:text-white group-hover:text-primary dark:group-hover:text-secondary-200 transition-colors">
                            {{ val.title }}
                        </h4>
                        <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
                            {{ val.description }}
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <MeetOurTeam />
    <CallToAction />
    <Footer />
</template>
