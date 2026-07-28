<script setup>
import { ref, watch } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    testimonials: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({ status: '', search: '' }),
    },
    stats: {
        type: Object,
        default: () => ({ total: 0, pending: 0, approved: 0, avg_rating: 5 }),
    },
});

defineOptions({ layout: AdminLayout });

const toast = useToast();

const search = ref(props.filters.search || '');
const selectedStatus = ref(props.filters.status || '');

const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const applyFilters = () => {
    router.get(
        route('admin.testimonials.index'),
        {
            search: search.value,
            status: selectedStatus.value,
        },
        { preserveState: true, replace: true }
    );
};

let searchTimeout;
watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 400);
});

const toggleApprove = (testimonial) => {
    router.patch(route('admin.testimonials.toggle-approve', testimonial.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Testimonial approval status updated.');
        },
    });
};

const openDeleteModal = (testimonial) => {
    itemToDelete.value = testimonial;
    isDeleteModalOpen.value = true;
};

const confirmDelete = () => {
    if (!itemToDelete.value) return;

    router.delete(route('admin.testimonials.destroy', itemToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            isDeleteModalOpen.value = false;
            itemToDelete.value = null;
            toast.success('Testimonial deleted.');
        },
    });
};

const getInitials = (name) => {
    if (!name) return 'CL';
    const parts = name.split(' ');
    if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
    return name.substring(0, 2).toUpperCase();
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    return new Date(dateStr).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
};
</script>

<template>
    <Head title="Testimonials Moderation - Admin Panel" />

    <div class="space-y-8">
        
        <!-- Header Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-xs">
            <div>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200">
                    💬 Client Reviews Moderation
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white mt-2 tracking-tight">
                    Testimonials & Rating Approvals
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Review visitor submissions, approve ratings, and manage client feedback shown on the public site.
                </p>
            </div>

            <Link 
                :href="route('testimonials')" 
                target="_blank"
                class="px-5 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-bold text-xs rounded-2xl transition flex items-center justify-center gap-1.5"
            >
                <span>Preview Public Page</span>
                <span>↗</span>
            </Link>
        </div>

        <!-- KPI Counter Chips -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-xs space-y-1">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Total Submissions</span>
                <div class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white">{{ stats.total }}</div>
                <span class="text-xs text-gray-500">Visitor Reviews</span>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-xs space-y-1">
                <span class="text-[10px] font-bold text-amber-500 uppercase tracking-widest block">Pending Review</span>
                <div class="text-2xl sm:text-3xl font-black text-amber-600 dark:text-amber-400">{{ stats.pending }}</div>
                <span class="text-xs text-gray-500">Needs Approval</span>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-xs space-y-1">
                <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest block">Approved</span>
                <div class="text-2xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400">{{ stats.approved }}</div>
                <span class="text-xs text-gray-500">Live on Website</span>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-xs space-y-1">
                <span class="text-[10px] font-bold text-primary dark:text-secondary-200 uppercase tracking-widest block">Average Rating</span>
                <div class="text-2xl sm:text-3xl font-black text-primary dark:text-secondary-200">★ {{ stats.avg_rating }}</div>
                <span class="text-xs text-gray-500">Client Score</span>
            </div>

        </div>

        <!-- Filter Toolbar -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-4 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
            
            <div class="relative w-full sm:w-80">
                <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input 
                    v-model="search"
                    type="text" 
                    placeholder="Search client name, email, or company..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-xs font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                />
            </div>

            <div class="flex items-center space-x-2">
                <button 
                    @click="selectedStatus = ''; applyFilters()" 
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition border"
                    :class="selectedStatus === '' ? 'bg-primary text-white border-primary shadow-xs' : 'bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600'"
                >
                    All ({{ stats.total }})
                </button>

                <button 
                    @click="selectedStatus = 'pending'; applyFilters()" 
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition border"
                    :class="selectedStatus === 'pending' ? 'bg-amber-600 text-white border-amber-600 shadow-xs' : 'bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600'"
                >
                    ⏳ Pending ({{ stats.pending }})
                </button>

                <button 
                    @click="selectedStatus = 'approved'; applyFilters()" 
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition border"
                    :class="selectedStatus === 'approved' ? 'bg-emerald-600 text-white border-emerald-600 shadow-xs' : 'bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600'"
                >
                    ✓ Approved ({{ stats.approved }})
                </button>
            </div>

        </div>

        <!-- Testimonials Moderation Table -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 dark:bg-gray-700/40 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="py-4 px-6">Client Info</th>
                            <th class="py-4 px-6">Rating</th>
                            <th class="py-4 px-6">Review Message</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6">Submitted Date</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 text-xs">
                        <tr 
                            v-for="item in testimonials.data" 
                            :key="item.id"
                            class="hover:bg-gray-50/80 dark:hover:bg-gray-700/30 transition"
                        >
                            <!-- Client Info -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div v-if="item.avatar_path" class="w-10 h-10 rounded-full overflow-hidden border border-gray-200 flex-shrink-0">
                                        <img :src="'/storage/' + item.avatar_path" class="w-full h-full object-cover" />
                                    </div>
                                    <div v-else class="w-10 h-10 rounded-full bg-gradient-to-tr from-primary to-indigo-600 text-white font-extrabold text-xs flex items-center justify-center flex-shrink-0">
                                        {{ getInitials(item.name) }}
                                    </div>

                                    <div>
                                        <div class="font-bold text-gray-900 dark:text-white text-sm">{{ item.name }}</div>
                                        <div class="text-gray-500 text-xs">{{ item.email }}</div>
                                        <div v-if="item.company" class="text-[10px] text-primary dark:text-secondary-200 font-semibold">{{ item.company }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Rating -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="flex items-center space-x-1 text-amber-400 font-bold text-sm">
                                    <span v-for="star in 5" :key="star">
                                        {{ star <= item.rating ? '★' : '☆' }}
                                    </span>
                                    <span class="text-gray-900 dark:text-white text-xs ml-1 font-extrabold">({{ item.rating }}/5)</span>
                                </div>
                            </td>

                            <!-- Review Message -->
                            <td class="py-4 px-6 max-w-xs">
                                <p class="text-gray-700 dark:text-gray-300 line-clamp-2 italic">
                                    "{{ item.message }}"
                                </p>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span 
                                    class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider inline-flex items-center gap-1"
                                    :class="[
                                        item.is_approved 
                                            ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' 
                                            : 'bg-amber-500/10 text-amber-600 dark:text-amber-400'
                                    ]"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full" :class="item.is_approved ? 'bg-emerald-500' : 'bg-amber-500'"></span>
                                    {{ item.is_approved ? 'Approved' : 'Pending Review' }}
                                </span>
                            </td>

                            <!-- Date -->
                            <td class="py-4 px-6 whitespace-nowrap text-gray-500">
                                {{ formatDate(item.created_at) }}
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end space-x-2">
                                    <button 
                                        @click="toggleApprove(item)"
                                        class="px-3 py-1 rounded-xl text-xs font-bold border transition shadow-xs flex items-center gap-1"
                                        :class="[
                                            item.is_approved 
                                                ? 'border-amber-300 text-amber-700 bg-amber-50 hover:bg-amber-100 dark:border-amber-800 dark:text-amber-300 dark:bg-amber-950/40' 
                                                : 'border-emerald-300 text-emerald-700 bg-emerald-50 hover:bg-emerald-100 dark:border-emerald-800 dark:text-emerald-300 dark:bg-emerald-950/40'
                                        ]"
                                    >
                                        <span>{{ item.is_approved ? '⏸️ Revoke' : '✓ Approve' }}</span>
                                    </button>

                                    <button 
                                        @click="openDeleteModal(item)"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                                        title="Delete Submission"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Empty State -->
                        <tr v-if="testimonials.data.length === 0">
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                No testimonial submissions found matching your filters.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="testimonials.links && testimonials.links.length > 3" class="p-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="text-xs text-gray-500">
                    Showing <span class="font-bold">{{ testimonials.from || 0 }}</span> to <span class="font-bold">{{ testimonials.to || 0 }}</span> of <span class="font-bold">{{ testimonials.total }}</span> reviews
                </div>

                <div class="flex items-center space-x-1">
                    <Component 
                        v-for="(link, i) in testimonials.links" 
                        :key="i"
                        :is="link.url ? Link : 'span'"
                        :href="link.url"
                        v-html="link.label"
                        class="px-3 py-1 rounded-lg text-xs font-semibold transition"
                        :class="[
                            link.active ? 'bg-primary text-white dark:bg-secondary-200 dark:text-gray-900' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700',
                            !link.url ? 'opacity-40 cursor-not-allowed' : ''
                        ]"
                    />
                </div>
            </div>
        </div>

        <!-- DELETE CONFIRMATION MODAL -->
        <div 
            v-if="isDeleteModalOpen && itemToDelete"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
            @click.self="isDeleteModalOpen = false"
        >
            <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-gray-200 dark:border-gray-700 space-y-4 text-center">
                <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 dark:bg-red-950/60 dark:text-red-400 flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>

                <h3 class="text-xl font-extrabold text-gray-900 dark:text-white">Delete Testimonial?</h3>
                <p class="text-xs text-gray-500 dark:text-gray-300 leading-relaxed">
                    Are you sure you want to delete the review submitted by <strong class="text-gray-900 dark:text-white">{{ itemToDelete.name }}</strong>?
                </p>

                <div class="flex items-center justify-center space-x-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button @click="isDeleteModalOpen = false" class="px-5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Cancel
                    </button>
                    <button @click="confirmDelete" class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow transition">
                        Delete Review
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>
