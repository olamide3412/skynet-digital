<script setup>
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

const props = defineProps({
    messages: {
        type: Array,
        default: () => []
    },
});

const toast = useToast();
const searchQuery = ref('');
const selectedServiceFilter = ref('');
const viewMode = ref('table'); // 'table' or 'cards'

const selectedMessage = ref(null);
const isModalOpen = ref(false);

const openMessageModal = (msg) => {
    selectedMessage.value = msg;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedMessage.value = null;
};

const copyEmail = (email) => {
    if (typeof navigator !== 'undefined' && navigator.clipboard) {
        navigator.clipboard.writeText(email)
            .then(() => toast.success('Email copied to clipboard!'))
            .catch(() => toast.error('Failed to copy email.'));
    }
};

const uniqueServices = computed(() => {
    const set = new Set();
    props.messages.forEach(m => {
        if (m.service) set.add(m.service);
    });
    return Array.from(set);
});

const filteredMessages = computed(() => {
    return props.messages.filter(msg => {
        // Service filter
        if (selectedServiceFilter.value && msg.service !== selectedServiceFilter.value) {
            return false;
        }

        // Search query filter
        if (!searchQuery.value.trim()) return true;
        const q = searchQuery.value.toLowerCase().trim();
        return (
            (msg.name && msg.name.toLowerCase().includes(q)) ||
            (msg.email && msg.email.toLowerCase().includes(q)) ||
            (msg.phone && msg.phone.toLowerCase().includes(q)) ||
            (msg.service && msg.service.toLowerCase().includes(q)) ||
            (msg.message && msg.message.toLowerCase().includes(q))
        );
    });
});
</script>

<template>
    <Head title="Contact Messages - Admin" />

    <div class="max-w-7xl mx-auto space-y-6 py-6 sm:px-6 lg:px-8">
        
        <!-- Header & Action Row -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200 dark:border-gray-800">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Contact Messages</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Review, search, and respond to client inquiries received from your website.</p>
            </div>
            
            <!-- Quick Metrics Badge -->
            <div class="flex items-center space-x-3">
                <div class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xs text-xs font-bold text-gray-700 dark:text-gray-300">
                    Total Inquiries: <span class="text-primary dark:text-secondary-200 font-extrabold text-sm ml-1">{{ messages.length }}</span>
                </div>
            </div>
        </div>

        <!-- Controls Toolbar: Search, Service Filter & View Toggle -->
        <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1">
                <!-- Live Search Input -->
                <div class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search by name, email, phone, or keyword..."
                        class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-gray-900/90 border border-gray-300 dark:border-gray-700 rounded-xl text-xs text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-primary focus:border-transparent transition"
                    />
                    <button 
                        v-if="searchQuery" 
                        @click="searchQuery = ''" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                    >
                        Clear
                    </button>
                </div>

                <!-- Service Filter Select -->
                <select 
                    v-model="selectedServiceFilter"
                    class="px-3 py-2 bg-gray-50 dark:bg-gray-900/90 border border-gray-300 dark:border-gray-700 rounded-xl text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition"
                >
                    <option value="">All Services</option>
                    <option v-for="svc in uniqueServices" :key="svc" :value="svc">{{ svc }}</option>
                </select>
            </div>

            <!-- View Switcher (Table vs Inbox Cards) -->
            <div class="flex items-center space-x-1.5 self-end sm:self-auto bg-gray-100 dark:bg-gray-900 p-1 rounded-xl border border-gray-200 dark:border-gray-700">
                <button 
                    @click="viewMode = 'table'"
                    class="px-3 py-1.5 text-xs font-semibold rounded-lg transition flex items-center space-x-1"
                    :class="viewMode === 'table' ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-xs' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'"
                    title="Table View"
                >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span>Table</span>
                </button>

                <button 
                    @click="viewMode = 'cards'"
                    class="px-3 py-1.5 text-xs font-semibold rounded-lg transition flex items-center space-x-1"
                    :class="viewMode === 'cards' ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-xs' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'"
                    title="Inbox Cards View"
                >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>Inbox</span>
                </button>
            </div>

        </div>

        <!-- Mode 1: Table View -->
        <div v-if="viewMode === 'table'" class="bg-white dark:bg-gray-800/90 overflow-hidden shadow-sm rounded-2xl border border-gray-200 dark:border-gray-700/80 p-6">
            
            <div v-if="filteredMessages.length === 0" class="text-center py-12 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <p class="text-base font-semibold text-gray-600 dark:text-gray-300">No contact messages found.</p>
                <p class="text-xs text-gray-400 mt-1">Try adjusting your search terms or service filters.</p>
            </div>

            <div v-else class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/60">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sender</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact Info</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Service Requested</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Message Preview</th>
                            <th scope="col" class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr 
                            v-for="message in filteredMessages" 
                            :key="message.id" 
                            @click="openMessageModal(message)"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition cursor-pointer"
                        >
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-gray-500 dark:text-gray-400">
                                {{ new Date(message.created_at).toLocaleDateString() }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        {{ message.name ? message.name.charAt(0).toUpperCase() : '?' }}
                                    </div>
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ message.name }}
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs font-medium text-gray-800 dark:text-gray-200">{{ message.email }}</div>
                                <div v-if="message.phone" class="text-[11px] text-gray-500 dark:text-gray-400">{{ message.phone }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-200 border border-blue-200 dark:border-blue-800">
                                    {{ message.service || 'General Inquiry' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-xs text-gray-600 dark:text-gray-300 max-w-xs truncate">
                                {{ message.message }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium" @click.stop>
                                <button 
                                    @click="openMessageModal(message)"
                                    class="px-3 py-1.5 rounded-lg text-primary dark:text-secondary-200 border border-primary/30 dark:border-secondary/40 hover:bg-primary/5 dark:hover:bg-secondary/10 font-semibold transition"
                                >
                                    Read Details
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mode 2: Inbox Cards View -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <div v-if="filteredMessages.length === 0" class="col-span-full text-center py-16 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 text-gray-400">
                <p class="text-base font-semibold text-gray-600 dark:text-gray-300">No contact messages found.</p>
                <p class="text-xs text-gray-400 mt-1">Try adjusting your search terms or service filters.</p>
            </div>

            <div 
                v-for="message in filteredMessages" 
                :key="message.id" 
                @click="openMessageModal(message)"
                class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm hover:shadow-md transition cursor-pointer flex flex-col justify-between group"
            >
                <div class="space-y-4">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                {{ message.name ? message.name.charAt(0).toUpperCase() : '?' }}
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-primary dark:group-hover:text-secondary-200 transition-colors">
                                    {{ message.name }}
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ message.email }}</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-semibold text-gray-400">{{ new Date(message.created_at).toLocaleDateString() }}</span>
                    </div>

                    <div>
                        <span class="inline-block px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-200 border border-blue-200 dark:border-blue-800 mb-2">
                            {{ message.service || 'General Inquiry' }}
                        </span>
                        <p class="text-xs text-gray-600 dark:text-gray-300 line-clamp-3 leading-relaxed">
                            "{{ message.message }}"
                        </p>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-xs" @click.stop>
                    <span v-if="message.phone" class="text-gray-500 dark:text-gray-400 font-medium">📞 {{ message.phone }}</span>
                    <span v-else class="text-gray-400 italic">No phone provided</span>

                    <button 
                        @click="openMessageModal(message)"
                        class="font-bold text-primary dark:text-secondary-200 hover:underline"
                    >
                        View Full Message →
                    </button>
                </div>
            </div>

        </div>

        <!-- Message Details Modal Popup -->
        <div 
            v-if="isModalOpen && selectedMessage"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs transition-opacity"
            @click.self="closeModal"
        >
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl max-w-2xl w-full shadow-2xl overflow-hidden transform transition-all">
                
                <!-- Modal Header -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-700/80 flex items-center justify-between bg-gray-50 dark:bg-gray-900/60">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm shadow">
                            {{ selectedMessage.name ? selectedMessage.name.charAt(0).toUpperCase() : '?' }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ selectedMessage.name }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Inquiry received on {{ new Date(selectedMessage.created_at).toLocaleString() }}</p>
                        </div>
                    </div>

                    <button 
                        @click="closeModal" 
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1.5 rounded-lg transition"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-6">
                    
                    <!-- Contact Metadata Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/60 border border-gray-100 dark:border-gray-700/60 text-xs">
                        <div>
                            <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">Email Address</span>
                            <span class="font-bold text-gray-900 dark:text-white select-all">{{ selectedMessage.email }}</span>
                        </div>

                        <div>
                            <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">Phone Number</span>
                            <span class="font-bold text-gray-900 dark:text-white select-all">{{ selectedMessage.phone || 'Not provided' }}</span>
                        </div>

                        <div>
                            <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">Service Requested</span>
                            <span class="font-bold text-primary dark:text-secondary-200">{{ selectedMessage.service || 'General Inquiry' }}</span>
                        </div>
                    </div>

                    <!-- Message Body Container -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Message Body</label>
                        <div class="p-5 rounded-2xl bg-gray-50 dark:bg-gray-900/90 border border-gray-200 dark:border-gray-700 text-sm text-gray-800 dark:text-gray-200 leading-relaxed whitespace-pre-wrap">
                            {{ selectedMessage.message }}
                        </div>
                    </div>

                </div>

                <!-- Modal Footer Actions -->
                <div class="p-6 border-t border-gray-100 dark:border-gray-700/80 bg-gray-50 dark:bg-gray-900/60 flex flex-wrap items-center justify-between gap-3">
                    <button 
                        @click="copyEmail(selectedMessage.email)" 
                        class="px-4 py-2 text-xs font-semibold rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                    >
                        📋 Copy Email Address
                    </button>

                    <div class="flex items-center space-x-3">
                        <button 
                            @click="closeModal" 
                            class="px-4 py-2 text-xs font-semibold rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                        >
                            Close
                        </button>

                        <a 
                            :href="`mailto:${selectedMessage.email}?subject=${encodeURIComponent('Re: ' + (selectedMessage.service || 'Inquiry') + ' - Skynet Digital')}`" 
                            target="_blank"
                            class="px-5 py-2 bg-primary hover:bg-primary-dark text-white font-bold text-xs rounded-xl shadow-md transition flex items-center"
                        >
                            ✉️ Reply via Email
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</template>
