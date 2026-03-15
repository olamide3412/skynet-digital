<script setup>
import HeroSection from '@/Components/Home/HeroSection.vue';
import CoreServices from '@/Components/Home/CoreServices.vue';
import WhyChooseUs from '@/Components/Home/WhyChooseUs.vue';
import CallToAction from '@/Components/Home/CallToAction.vue';
import MeetOurTeam from '@/Components/Home/MeetOurTeam.vue';
import Layout from '@/Layouts/Layout.vue';
import Footer from '@/Components/Footer.vue';
import { Head } from '@inertiajs/vue3';

defineOptions({ layout: Layout })
</script>

<template>
    <Head>
        <title>Home - Skynet Digital Limited</title>
        <meta name="description" content="Skynet Digital Limited - Your trusted partner for modern technology solutions, networking, product development, and IT training in Nigeria." />
        <meta name="keywords" content="Skynet Digital, Tech Company Nigeria, Networking, Product Development, IT Training, AI, Tech Solutions Delta State" />
    </Head>
    <div>
        <HeroSection/>
        <CoreServices/>
        <WhyChooseUs/>
        <MeetOurTeam/>
        <CallToAction/>
        <Footer/>
    </div>
</template>
