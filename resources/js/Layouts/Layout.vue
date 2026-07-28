<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Navbar from '@/Components/Navigations/Navbar.vue';
import WhatsAppButton from '@/Components/WhatsAppButton.vue';
import FlashMessages from '@/Components/FlashMessages.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const page = usePage();

const isAdminPage = computed(() => {
    const component = page.component;
    return (
        component.startsWith('Admin/') ||
        (component.startsWith('Auth/') && component !== 'Auth/Login')
    );
});
</script>

<template>
    <AdminLayout v-if="isAdminPage">
        <slot />
    </AdminLayout>
    <div v-else class="bg-light text-black dark:bg-dark dark:text-white min-h-screen flex flex-col justify-between">
        <div>
            <Navbar />
            <main class="pt-16">
                <slot />
                <WhatsAppButton v-if="!$page.props.auth.user" />
            </main>
        </div>
        <FlashMessages />
    </div>
</template>
