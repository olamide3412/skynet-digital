<script setup>
import Layout from '@/Layouts/Layout.vue';
import { Head } from '@inertiajs/vue3';
import ContactForm from '@/Components/Contact/ContactForm.vue';
import Footer from '@/Components/Footer.vue';

defineOptions({ layout: Layout })
</script>

<template>
    <Head>
        <title>Contact Us - Skynet Digital Limited</title>
        <meta name="description" content="Get in touch with Skynet Digital Limited for technical support, project inquiries, or any other questions. We're here to help." />
    </Head>
    <ContactForm />
    <Footer/>
</template>
