<script setup>
import Layout from '@/Layouts/Layout.vue';
import { Head } from '@inertiajs/vue3';
import ServiceList from '@/Components/Services/ServiceList.vue';
import CallToAction from '@/Components/Home/CallToAction.vue';
import Footer from '@/Components/Footer.vue';

defineOptions({ layout: Layout })
</script>

<template>
    <Head>
        <title>Our Services - Skynet Digital Limited</title>
        <meta name="description" content="Explore our wide range of technology services including Networking, Product Development, Graphic Design, IT Training, and Maintenance." />
    </Head>

    <div class="bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-base font-semibold text-primary tracking-wide uppercase">Our Services</h1>
                <p class="mt-1 text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl sm:tracking-tight lg:text-6xl">
                    What We Offer
                </p>
                <p class="max-w-xl mt-5 mx-auto text-xl text-gray-500 dark:text-gray-300">
                    Discover how our technology solutions can help you achieve your business goals.
                </p>
            </div>
        </div>
    </div>

    <ServiceList />
    <CallToAction />
    <Footer/>
</template>
