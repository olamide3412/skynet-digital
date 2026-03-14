<script setup>
import Layout from '@/Layouts/Layout.vue';
import { Head } from '@inertiajs/vue3';
import CallToAction from '@/Components/Home/CallToAction.vue';
import MeetOurTeam from '@/Components/Home/MeetOurTeam.vue';
import Footer from '@/Components/Footer.vue';

defineOptions({ layout: Layout })
</script>

<template>
    <Head>
        <title>About Us - Skynet Digital Limited</title>
        <meta name="description" content="Learn more about Skynet Digital Limited, our mission, vision, and the passionate team innovating for the future of technology in Africa." />
    </Head>

    <div class="bg-white dark:bg-gray-900">
        <!-- Hero section -->
        <div class="relative overflow-hidden">
            <div class="absolute inset-y-0 h-full w-full" aria-hidden="true">
                <div class="relative h-full">
                    <svg class="absolute right-full transform translate-y-1/3 translate-x-1/4 md:translate-y-1/2 sm:translate-x-1/2 lg:translate-x-full" width="404" height="784" fill="none" viewBox="0 0 404 784">
                        <defs>
                            <pattern id="e229dbec-10e9-49ee-8ec3-0286ca089edf" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                <rect x="0" y="0" width="4" height="4" class="text-gray-200 dark:text-gray-800" fill="currentColor" />
                            </pattern>
                        </defs>
                        <rect width="404" height="784" fill="url(#e229dbec-10e9-49ee-8ec3-0286ca089edf)" />
                    </svg>
                    <svg class="absolute left-full transform -translate-y-3/4 -translate-x-1/4 sm:-translate-x-1/2 md:translate-y-1/2 lg:-translate-x-1/2" width="404" height="784" fill="none" viewBox="0 0 404 784">
                        <defs>
                            <pattern id="d2a68204-c383-44b1-b99f-42ccff4e5365" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                <rect x="0" y="0" width="4" height="4" class="text-gray-200 dark:text-gray-800" fill="currentColor" />
                            </pattern>
                        </defs>
                        <rect width="404" height="784" fill="url(#d2a68204-c383-44b1-b99f-42ccff4e5365)" />
                    </svg>
                </div>
            </div>

            <div class="relative pt-6 pb-16 sm:pb-24">
                <div class="mt-16 mx-auto max-w-7xl px-4 sm:mt-24 sm:px-6">
                    <div class="text-center">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white sm:text-5xl md:text-6xl">
                            <span class="block">Who We Are</span>
                            <span class="block text-primary">Innovating for the Future</span>
                        </h1>
                        <p class="mt-3 max-w-md mx-auto text-base text-gray-500 dark:text-gray-300 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                            We are a technology service provider dedicated to helping businesses and individuals adopt, manage, and grow with modern digital solutions. Our team combines technical expertise with business understanding to deliver systems that are reliable, secure, and easy to use.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mission Vision Values -->
        <div class="relative bg-gray-50 dark:bg-gray-800 py-16 sm:py-24">
            <div class="lg:mx-auto lg:max-w-7xl lg:px-8 lg:grid lg:grid-cols-2 lg:gap-24 lg:items-start">
                <div class="relative sm:py-16 lg:py-0">
                    <div class="relative mx-auto max-w-md px-4 sm:max-w-3xl sm:px-6 lg:px-0 lg:max-w-none lg:py-20">
                        <!-- Testimonial card area -->
                         <div class="relative pt-64 pb-10 rounded-2xl shadow-xl overflow-hidden hidden lg:block">
                            <img class="absolute inset-0 h-full w-full object-cover" src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-1.2.1&auto=format&fit=crop&w=2850&q=80" alt="">
                            <div class="absolute inset-0 bg-primary mix-blend-multiply opacity-60"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary opacity-60"></div>
                        </div>
                    </div>
                </div>

                <div class="relative mx-auto max-w-md px-4 sm:max-w-3xl sm:px-6 lg:px-0">
                    <!-- Content area -->
                    <div class="pt-12 sm:pt-16 lg:pt-20">
                        <h2 class="text-3xl text-gray-900 dark:text-white font-extrabold tracking-tight sm:text-4xl">
                            Our Mission & Vision
                        </h2>
                        <div class="mt-6 text-gray-500 dark:text-gray-300 space-y-6">
                            <p class="text-lg">
                                <strong>Our Mission:</strong> To simplify technology for businesses and empower people through practical digital solutions and training.
                            </p>
                            <p class="text-lg">
                                <strong>Our Vision:</strong> To become a trusted technology partner for organizations across Africa and beyond.
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-10">
                         <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Our Values</h3>
                         <dl class="mt-4 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 sm:gap-y-16 lg:gap-x-8">
                            <div class="border-t border-gray-200 pt-4">
                                <dt class="font-medium text-gray-900 dark:text-white">Integrity</dt>
                                <dd class="mt-2 text-sm text-gray-500 dark:text-gray-300">We are honest, transparent, and committed to doing what's best for our clients.</dd>
                            </div>
                            <div class="border-t border-gray-200 pt-4">
                                <dt class="font-medium text-gray-900 dark:text-white">Innovation</dt>
                                <dd class="mt-2 text-sm text-gray-500 dark:text-gray-300">We constantly explore new technologies to deliver better solutions.</dd>
                            </div>
                             <div class="border-t border-gray-200 pt-4">
                                <dt class="font-medium text-gray-900 dark:text-white">Reliability</dt>
                                <dd class="mt-2 text-sm text-gray-500 dark:text-gray-300">We build systems you can count on, day within and day out.</dd>
                            </div>
                             <div class="border-t border-gray-200 pt-4">
                                <dt class="font-medium text-gray-900 dark:text-white">Customer Success</dt>
                                <dd class="mt-2 text-sm text-gray-500 dark:text-gray-300">Your growth is our priority. We succeed when you succeed.</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <MeetOurTeam />
        
        <CallToAction/>
        <Footer/>
    </div>
</template>
