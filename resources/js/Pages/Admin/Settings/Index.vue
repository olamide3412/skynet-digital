<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useToast } from 'vue-toastification';

const props = defineProps({
    settings: {
        type: Object,
        default: () => ({}),
    },
    isSuperAdmin: {
        type: Boolean,
        default: false,
    },
});

defineOptions({ layout: AdminLayout });

const toast = useToast();

const form = useForm({
    contact_email: props.settings.contact_email || 'info@skynetdigital.com',
    contact_phone: props.settings.contact_phone || '+234 803 207 2831',
    contact_whatsapp: props.settings.contact_whatsapp || '2348032072831',
    contact_address: props.settings.contact_address || 'Delta State, Nigeria',
    contact_hours: props.settings.contact_hours || 'Monday – Friday: 8:00 AM – 6:00 PM',
    rc_number: props.settings.rc_number || 'RC 9168845',
});

const submit = () => {
    if (!props.isSuperAdmin) return;
    form.post(route('admin.settings.update'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('System contact settings updated successfully!');
        },
        onError: () => {
            toast.error('Failed to update settings. Please check errors.');
        },
    });
};
</script>

<template>
    <Head title="System Contact Settings" />

    <div class="max-w-6xl mx-auto py-6 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header Row -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-gray-200 dark:border-gray-800 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
                    <span>⚙️ System Contact Settings</span>
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Manage public contact info, phone numbers, email addresses, and company details shown across the website.
                </p>
            </div>
            <div>
                <span v-if="isSuperAdmin" class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200 border border-green-200">
                    👑 Full Access (Super Admin)
                </span>
                <span v-else class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200 border border-amber-200">
                    🔒 Read-Only Access
                </span>
            </div>
        </div>

        <!-- Read-Only Banner Notice for Non-Super-Admins -->
        <div v-if="!isSuperAdmin" class="bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/80 rounded-2xl p-4 flex items-center gap-3 text-amber-800 dark:text-amber-200 text-xs font-semibold shadow-xs">
            <span class="text-base flex-shrink-0">🔒</span>
            <span>Read-Only Mode: You can view the live system contact settings. Only Super Administrators can save changes or update settings.</span>
        </div>

        <form @submit.prevent="submit" class="space-y-8">
            
            <!-- Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Card 1: Email Address -->
                <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center space-x-3 pb-3 border-b border-gray-100 dark:border-gray-700">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-900/40 dark:text-blue-200 flex items-center justify-center font-bold">
                            ✉️
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900 dark:text-white">Contact Email Address</h3>
                            <p class="text-[11px] text-gray-400">Primary email displayed on Contact page & Footer.</p>
                        </div>
                    </div>

                    <div>
                        <label for="contact_email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input 
                            id="contact_email" 
                            v-model="form.contact_email" 
                            type="email" 
                            placeholder="e.g. info@skynetdigital.com" 
                            :disabled="!isSuperAdmin"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition disabled:opacity-70 disabled:cursor-not-allowed"
                            required 
                        />
                        <div v-if="form.errors.contact_email" class="text-red-500 text-xs mt-1.5 font-medium">{{ form.errors.contact_email }}</div>
                    </div>
                </div>

                <!-- Card 2: Phone Number -->
                <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center space-x-3 pb-3 border-b border-gray-100 dark:border-gray-700">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-200 flex items-center justify-center font-bold">
                            📞
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900 dark:text-white">Primary Phone Number</h3>
                            <p class="text-[11px] text-gray-400">Main hotline for incoming calls and inquiries.</p>
                        </div>
                    </div>

                    <div>
                        <label for="contact_phone" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input 
                            id="contact_phone" 
                            v-model="form.contact_phone" 
                            type="text" 
                            placeholder="e.g. +234 803 207 2831" 
                            :disabled="!isSuperAdmin"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition disabled:opacity-70 disabled:cursor-not-allowed"
                            required 
                        />
                        <div v-if="form.errors.contact_phone" class="text-red-500 text-xs mt-1.5 font-medium">{{ form.errors.contact_phone }}</div>
                    </div>
                </div>

                <!-- Card 3: WhatsApp Contact Number -->
                <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center space-x-3 pb-3 border-b border-gray-100 dark:border-gray-700">
                        <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 dark:bg-green-900/40 dark:text-green-200 flex items-center justify-center font-bold">
                            💬
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900 dark:text-white">WhatsApp Number</h3>
                            <p class="text-[11px] text-gray-400">Used for direct WhatsApp chat links.</p>
                        </div>
                    </div>

                    <div>
                        <label for="contact_whatsapp" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                            WhatsApp Number (with country code)
                        </label>
                        <input 
                            id="contact_whatsapp" 
                            v-model="form.contact_whatsapp" 
                            type="text" 
                            placeholder="e.g. 2348032072831 or +234 803 207 2831" 
                            :disabled="!isSuperAdmin"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition disabled:opacity-70 disabled:cursor-not-allowed"
                        />
                        <div v-if="form.errors.contact_whatsapp" class="text-red-500 text-xs mt-1.5 font-medium">{{ form.errors.contact_whatsapp }}</div>
                    </div>
                </div>

                <!-- Card 4: Office Location / Address -->
                <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center space-x-3 pb-3 border-b border-gray-100 dark:border-gray-700">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 dark:bg-purple-900/40 dark:text-purple-200 flex items-center justify-center font-bold">
                            📍
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900 dark:text-white">Office Headquarters / Location</h3>
                            <p class="text-[11px] text-gray-400">Physical address or state location.</p>
                        </div>
                    </div>

                    <div>
                        <label for="contact_address" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                            Location Address
                        </label>
                        <input 
                            id="contact_address" 
                            v-model="form.contact_address" 
                            type="text" 
                            placeholder="e.g. Delta State, Nigeria" 
                            :disabled="!isSuperAdmin"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition disabled:opacity-70 disabled:cursor-not-allowed"
                        />
                        <div v-if="form.errors.contact_address" class="text-red-500 text-xs mt-1.5 font-medium">{{ form.errors.contact_address }}</div>
                    </div>
                </div>

                <!-- Card 5: Business Hours -->
                <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center space-x-3 pb-3 border-b border-gray-100 dark:border-gray-700">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-900/40 dark:text-amber-200 flex items-center justify-center font-bold">
                            ⏰
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900 dark:text-white">Business Hours</h3>
                            <p class="text-[11px] text-gray-400">Operating hours displayed on Contact page.</p>
                        </div>
                    </div>

                    <div>
                        <label for="contact_hours" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                            Working Hours
                        </label>
                        <input 
                            id="contact_hours" 
                            v-model="form.contact_hours" 
                            type="text" 
                            placeholder="e.g. Monday – Friday: 8:00 AM – 6:00 PM" 
                            :disabled="!isSuperAdmin"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition disabled:opacity-70 disabled:cursor-not-allowed"
                        />
                        <div v-if="form.errors.contact_hours" class="text-red-500 text-xs mt-1.5 font-medium">{{ form.errors.contact_hours }}</div>
                    </div>
                </div>

                <!-- Card 6: CAC Registration Number -->
                <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center space-x-3 pb-3 border-b border-gray-100 dark:border-gray-700">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-200 flex items-center justify-center font-bold">
                            🛡️
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900 dark:text-white">CAC Registration Number</h3>
                            <p class="text-[11px] text-gray-400">Official company RC registration number.</p>
                        </div>
                    </div>

                    <div>
                        <label for="rc_number" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                            RC Number
                        </label>
                        <input 
                            id="rc_number" 
                            v-model="form.rc_number" 
                            type="text" 
                            placeholder="e.g. RC 9168845" 
                            :disabled="!isSuperAdmin"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition disabled:opacity-70 disabled:cursor-not-allowed"
                        />
                        <div v-if="form.errors.rc_number" class="text-red-500 text-xs mt-1.5 font-medium">{{ form.errors.rc_number }}</div>
                    </div>
                </div>

            </div>

            <!-- Submit Button Row -->
            <div class="flex justify-end pt-4">
                <button 
                    v-if="isSuperAdmin"
                    type="submit" 
                    :disabled="form.processing" 
                    class="py-3.5 px-8 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition transform active:scale-98 disabled:opacity-50 flex items-center justify-center text-sm"
                >
                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>{{ form.processing ? 'Saving Changes...' : 'Save Settings' }}</span>
                </button>
                <div v-else class="px-5 py-3 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 font-bold text-xs flex items-center gap-2 border border-gray-200 dark:border-gray-700">
                    <span>🔒 Read-Only Access (Only Super Administrator can save changes)</span>
                </div>
            </div>

        </form>
    </div>
</template>
