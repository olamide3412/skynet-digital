<script setup>
import Layout from '@/Layouts/Layout.vue';
import { Head } from '@inertiajs/vue3';
import ProjectList from '@/Components/Portfolio/ProjectList.vue';
import CallToAction from '@/Components/Home/CallToAction.vue';
import Footer from '@/Components/Footer.vue';

defineProps({
    projects: {
        type: Array,
        required: true,
    }
});

defineOptions({ layout: Layout })
</script>

<template>
    <Head>
        <title>Portfolio - Skynet Digital Limited</title>
        <meta name="description" content="Discover our latest projects and technical achievements at Skynet Digital Limited." />
    </Head>

    <ProjectList :projects="projects" />
    <CallToAction />
    <Footer/>
</template>
