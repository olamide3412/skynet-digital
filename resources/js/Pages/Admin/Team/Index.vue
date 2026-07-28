<script setup>
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    teamMembers: {
        type: Array,
        required: true,
    },
});

defineOptions({ layout: AdminLayout });

const toast = useToast();

// Modal State
const isModalOpen = ref(false);
const isEditMode = ref(false);
const editingMemberId = ref(null);
const imagePreview = ref(null);

const isDeleteModalOpen = ref(false);
const memberToDelete = ref(null);

// Form
const form = useForm({
    name: '',
    role: '',
    bio: '',
    initials: '',
    gradient: 'from-primary to-indigo-600',
    order: 0,
    is_active: true,
    image: null,
});

// Preset Gradients Options
const gradientOptions = [
    { label: 'Primary to Indigo (Blue/Purple)', value: 'from-primary to-indigo-600' },
    { label: 'Indigo to Purple', value: 'from-indigo-600 to-purple-600' },
    { label: 'Purple to Pink', value: 'from-purple-600 to-pink-600' },
    { label: 'Amber to Red', value: 'from-amber-500 to-red-600' },
    { label: 'Emerald to Teal', value: 'from-emerald-500 to-teal-600' },
    { label: 'Blue to Cyan', value: 'from-blue-600 to-cyan-500' },
];

const openCreateModal = () => {
    isEditMode.value = false;
    editingMemberId.value = null;
    imagePreview.value = null;
    form.reset();
    form.gradient = 'from-primary to-indigo-600';
    form.order = props.teamMembers.length + 1;
    form.is_active = true;
    form.clearErrors();
    isModalOpen.value = true;
};

const openEditModal = (member) => {
    isEditMode.value = true;
    editingMemberId.value = member.id;
    imagePreview.value = member.image_path ? '/storage/' + member.image_path : null;
    form.name = member.name;
    form.role = member.role;
    form.bio = member.bio || '';
    form.initials = member.initials || '';
    form.gradient = member.gradient || 'from-primary to-indigo-600';
    form.order = member.order || 0;
    form.is_active = member.is_active;
    form.image = null;
    form.clearErrors();
    isModalOpen.value = true;
};

const handleImageUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const submitForm = () => {
    if (isEditMode.value) {
        form.post(route('admin.team.update', editingMemberId.value), {
            preserveScroll: true,
            onSuccess: () => {
                isModalOpen.value = false;
                form.reset();
                toast.success('Team member details updated.');
            },
            onError: () => {
                toast.error('Please fix errors in the form.');
            },
        });
    } else {
        form.post(route('admin.team.store'), {
            preserveScroll: true,
            onSuccess: () => {
                isModalOpen.value = false;
                form.reset();
                toast.success('Team member created successfully.');
            },
            onError: () => {
                toast.error('Please fix errors in the form.');
            },
        });
    }
};

const toggleActive = (member) => {
    router.patch(route('admin.team.toggle-active', member.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Team member status updated.');
        },
    });
};

const openDeleteModal = (member) => {
    memberToDelete.value = member;
    isDeleteModalOpen.value = true;
};

const confirmDelete = () => {
    if (!memberToDelete.value) return;

    router.delete(route('admin.team.destroy', memberToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            isDeleteModalOpen.value = false;
            memberToDelete.value = null;
            toast.success('Team member removed.');
        },
    });
};

// Helper: Initials generator if empty
const getInitials = (name) => {
    if (!name) return 'TM';
    const parts = name.split(' ');
    if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
    return name.substring(0, 3).toUpperCase();
};
</script>

<template>
    <Head title="Team Management - Super Admin" />

    <div class="space-y-8">
        
        <!-- Header Banner & Action Row -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-xs">
            <div>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200">
                    👑 Leadership Showcase
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white mt-2 tracking-tight">
                    Team Members Management
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Add, edit, reorder, and manage leadership & talent profiles shown on the public Home Page.
                </p>
            </div>

            <button 
                @click="openCreateModal"
                class="px-6 py-3.5 bg-primary hover:bg-primary-dark text-white font-bold text-xs sm:text-sm rounded-2xl shadow-md hover:shadow-lg transition flex items-center justify-center gap-2 flex-shrink-0"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Team Member</span>
            </button>
        </div>

        <!-- Team Showcase Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <div 
                v-for="member in teamMembers" 
                :key="member.id"
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4 group relative"
            >
                <!-- Order & Active Badges -->
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                        Order Position: #{{ member.order }}
                    </span>

                    <button 
                        @click="toggleActive(member)"
                        class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider transition flex items-center gap-1.5"
                        :class="[
                            member.is_active 
                                ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' 
                                : 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20'
                        ]"
                    >
                        <span class="w-1.5 h-1.5 rounded-full" :class="member.is_active ? 'bg-emerald-500' : 'bg-amber-500'"></span>
                        {{ member.is_active ? 'Active on Home' : 'Hidden' }}
                    </button>
                </div>

                <!-- Avatar Badge or Photo -->
                <div class="text-center space-y-3">
                    <div v-if="member.image_path" class="mx-auto w-24 h-24 rounded-full overflow-hidden border-4 border-white dark:border-gray-700 shadow-md">
                        <img :src="'/storage/' + member.image_path" :alt="member.name" class="w-full h-full object-cover" />
                    </div>
                    <div 
                        v-else 
                        class="mx-auto w-24 h-24 rounded-full bg-gradient-to-tr text-white font-extrabold text-2xl flex items-center justify-center shadow-md border-4 border-white dark:border-gray-700"
                        :class="member.gradient || 'from-primary to-indigo-600'"
                    >
                        {{ member.initials || getInitials(member.name) }}
                    </div>

                    <div>
                        <h3 class="text-base sm:text-lg font-extrabold text-gray-900 dark:text-white">
                            {{ member.name }}
                        </h3>
                        <p class="text-xs font-bold text-primary dark:text-secondary-200 uppercase tracking-wider mt-0.5">
                            {{ member.role }}
                        </p>
                    </div>

                    <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed line-clamp-3 pt-2 border-t border-gray-100 dark:border-gray-700/60">
                        {{ member.bio || 'No bio specified.' }}
                    </p>
                </div>

                <!-- Card Action Controls -->
                <div class="pt-3 border-t border-gray-100 dark:border-gray-700/80 flex items-center justify-end space-x-2">
                    <button 
                        @click="openEditModal(member)"
                        class="px-3 py-1.5 rounded-xl text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 transition flex items-center gap-1"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Edit</span>
                    </button>

                    <button 
                        @click="openDeleteModal(member)"
                        class="px-3 py-1.5 rounded-xl text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 dark:bg-red-950/40 dark:text-red-400 border border-red-200 dark:border-red-800 transition flex items-center gap-1"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Delete</span>
                    </button>
                </div>

            </div>

            <!-- Empty State -->
            <div v-if="teamMembers.length === 0" class="col-span-full text-center py-16 bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 text-gray-400 space-y-3">
                <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-base font-semibold text-gray-700 dark:text-gray-300">No team members registered yet.</p>
                <button @click="openCreateModal" class="px-4 py-2 bg-primary text-white font-bold text-xs rounded-xl shadow">
                    Create First Member
                </button>
            </div>

        </div>

        <!-- CREATE / EDIT TEAM MEMBER MODAL -->
        <div 
            v-if="isModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
            @click.self="isModalOpen = false"
        >
            <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-gray-200 dark:border-gray-700 space-y-6 max-h-[90vh] overflow-y-auto">
                
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white">
                        {{ isEditMode ? 'Edit Team Member Profile' : 'Add New Team Member' }}
                    </h3>
                    <button @click="isModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="space-y-4">
                    
                    <!-- Live Avatar Preview in Modal -->
                    <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/40 rounded-2xl border border-gray-200 dark:border-gray-600">
                        <div v-if="imagePreview" class="w-16 h-16 rounded-full overflow-hidden border-2 border-primary shadow flex-shrink-0">
                            <img :src="imagePreview" class="w-full h-full object-cover" />
                        </div>
                        <div 
                            v-else 
                            class="w-16 h-16 rounded-full bg-gradient-to-tr text-white font-black text-xl flex items-center justify-center shadow flex-shrink-0"
                            :class="form.gradient"
                        >
                            {{ form.initials || getInitials(form.name) }}
                        </div>

                        <div>
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300 block">Avatar Card Badge Preview</span>
                            <span class="text-[11px] text-gray-500 dark:text-gray-400">Upload a profile photo or select a gradient for initials emblem.</span>
                        </div>
                    </div>

                    <!-- Name & Role -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Full Name *</label>
                            <input v-model="form.name" type="text" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white" required placeholder="Olamide Johnson Isaiah" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Role / Position Title *</label>
                            <input v-model="form.role" type="text" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white" required placeholder="CEO / Founder" />
                        </div>
                    </div>

                    <!-- Initials & Gradient -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Initials (e.g. OJI)</label>
                            <input v-model="form.initials" type="text" maxlength="6" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white uppercase" placeholder="OJI" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Gradient Theme</label>
                            <select v-model="form.gradient" class="w-full px-3 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-xs font-bold text-gray-900 dark:text-white cursor-pointer">
                                <option v-for="g in gradientOptions" :key="g.value" :value="g.value" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-bold">{{ g.label }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Profile Photo File Upload -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Profile Photo (Optional)</label>
                        <input @change="handleImageUpload" type="file" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary dark:file:bg-secondary/20 dark:file:text-secondary-200 hover:file:bg-primary/20" />
                    </div>

                    <!-- Bio -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Bio Description</label>
                        <textarea v-model="form.bio" rows="3" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-xs font-medium text-gray-900 dark:text-white" placeholder="Visionary technology leader..."></textarea>
                    </div>

                    <!-- Order & Active Status -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Order Position</label>
                            <input v-model="form.order" type="number" min="0" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Visibility Status</label>
                            <select v-model="form.is_active" class="w-full px-3 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-xs font-bold text-gray-900 dark:text-white cursor-pointer">
                                <option :value="true" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-bold">Active (Visible on Home)</option>
                                <option :value="false" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-bold">Hidden</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="isModalOpen = false" class="px-5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            Cancel
                        </button>
                        <button type="submit" :disabled="form.processing" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dark text-white text-xs font-bold shadow transition disabled:opacity-50">
                            {{ isEditMode ? 'Save Member' : 'Create Member' }}
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <!-- DELETE CONFIRMATION MODAL -->
        <div 
            v-if="isDeleteModalOpen && memberToDelete"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
            @click.self="isDeleteModalOpen = false"
        >
            <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-gray-200 dark:border-gray-700 space-y-4 text-center">
                <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 dark:bg-red-950/60 dark:text-red-400 flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>

                <h3 class="text-xl font-extrabold text-gray-900 dark:text-white">Delete Team Member?</h3>
                <p class="text-xs text-gray-500 dark:text-gray-300 leading-relaxed">
                    Are you sure you want to permanently delete <strong class="text-gray-900 dark:text-white">{{ memberToDelete.name }}</strong> ({{ memberToDelete.role }})?
                </p>

                <div class="flex items-center justify-center space-x-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button @click="isDeleteModalOpen = false" class="px-5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Cancel
                    </button>
                    <button @click="confirmDelete" class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow transition">
                        Delete Member
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>
