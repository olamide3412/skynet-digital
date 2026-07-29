<script setup>
import { ref, watch, computed, onMounted } from 'vue';
import { Head, useForm, Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
    isSuperAdmin: {
        type: Boolean,
        default: false,
    },
    users: {
        type: Array,
        default: () => [],
    },
});

const serviceOptions = [
    'Web Application / Web Development',
    'Frontend Development',
    'Backend & API Development',
    'Full-Stack Development',
    'UI/UX Design',
    'Graphic Design & Branding',
    'Mobile App Development',
    'Cloud Infrastructure & DevOps',
    'Cybersecurity & IT Audit',
    'Digital Marketing & SEO',
    'Other'
];

const categoryOptions = [
    'SaaS Platform',
    'Enterprise System',
    'E-Commerce & Retail Portal',
    'Corporate / Institutional Website',
    'Mobile Application (iOS / Android)',
    'Brand Identity & Design System',
    'Custom Software / Microservices',
    'Other'
];

const form = useForm({
    title: props.project.title,
    industry: props.project.industry || '',
    service: props.project.service || '',
    category: props.project.category || '',
    description: props.project.description || '',
    demo_url: props.project.demo_url || '',
    image_path: null,
    is_published: props.project.is_published ?? true,
    meta_title: props.project.meta_title || '',
    meta_description: props.project.meta_description || '',
    meta_keywords: props.project.meta_keywords || '',
    media_files: [],
    media_urls: [],
    removed_media_ids: [],
    user_id: props.project.user_id || null,
    remove_cover_image: false,
    _method: 'PUT',
});

const getInitials = (name) => {
    if (!name) return '??';
    const parts = name.split(' ');
    if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
    return name.substring(0, 2).toUpperCase();
};

const selectedOwner = computed(() => {
    if (!form.user_id) return null;
    return props.users.find(u => u.id === form.user_id) || null;
});

// Initialize Service mode
const initialServiceOpt = serviceOptions.find(opt => opt === props.project.service);
const selectedServiceMode = ref(initialServiceOpt ? initialServiceOpt : (props.project.service ? 'Other' : ''));
const customService = ref(initialServiceOpt ? '' : props.project.service);

// Initialize Category mode
const initialCategoryOpt = categoryOptions.find(opt => opt === props.project.category);
const selectedCategoryMode = ref(initialCategoryOpt ? initialCategoryOpt : (props.project.category ? 'Other' : ''));
const customCategory = ref(initialCategoryOpt ? '' : props.project.category);

const imagePreview = ref(props.project.image_path ? `/storage/${props.project.image_path}` : null);
const fileInput = ref(null);
const galleryFileInput = ref(null);
const textareaRef = ref(null);

// Populate existing project media
const galleryItems = ref([]);

onMounted(() => {
    if (props.project.media && props.project.media.length > 0) {
        galleryItems.value = props.project.media.map(m => ({
            id: m.id,
            isExisting: true,
            source: m.source_type,
            type: m.type,
            file: null,
            url: m.url,
            previewUrl: m.source_type === 'file' ? `/storage/${m.file_path}` : m.url,
            name: m.source_type === 'file' ? m.file_path : m.url
        }));
    }
});

const newUrlType = ref('image');
const newUrlInput = ref('');

const totalMediaCount = computed(() => galleryItems.value.length);
const canAddMore = computed(() => totalMediaCount.value < 15);

const triggerFileInput = () => {
    fileInput.value?.click();
};

const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image_path = file;
        form.remove_cover_image = false;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const removeImage = () => {
    form.image_path = null;
    form.remove_cover_image = true;
    imagePreview.value = null;
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

// Gallery File Upload Handler
const triggerGalleryFileInput = () => {
    galleryFileInput.value?.click();
};

const handleGalleryFiles = (e) => {
    const files = Array.from(e.target.files);
    for (const file of files) {
        if (galleryItems.value.length >= 15) break;
        const isVideo = file.type.startsWith('video/');
        galleryItems.value.push({
            id: 'new_file_' + Date.now() + '_' + Math.random().toString(36).substring(2, 7),
            isExisting: false,
            source: 'file',
            type: isVideo ? 'video' : 'image',
            file: file,
            url: null,
            previewUrl: URL.createObjectURL(file),
            name: file.name
        });
    }
    syncFormMedia();
    if (galleryFileInput.value) galleryFileInput.value.value = '';
};

// Add External Media URL Handler
const addMediaUrl = () => {
    if (!newUrlInput.value.trim()) return;
    if (galleryItems.value.length >= 15) return;

    galleryItems.value.push({
        id: 'new_url_' + Date.now() + '_' + Math.random().toString(36).substring(2, 7),
        isExisting: false,
        source: 'url',
        type: newUrlType.value,
        file: null,
        url: newUrlInput.value.trim(),
        previewUrl: newUrlInput.value.trim(),
        name: newUrlInput.value.trim()
    });

    newUrlInput.value = '';
    syncFormMedia();
};

// Remove item from Gallery
const removeGalleryItem = (index) => {
    const removed = galleryItems.value[index];
    if (removed.isExisting) {
        form.removed_media_ids.push(removed.id);
    }
    galleryItems.value.splice(index, 1);
    syncFormMedia();
};

// Sync form media fields
const syncFormMedia = () => {
    form.media_files = galleryItems.value.filter(i => !i.isExisting && i.source === 'file').map(i => i.file);
    form.media_urls = galleryItems.value.filter(i => !i.isExisting && i.source === 'url').map(i => ({ type: i.type, url: i.url }));
};

// Sync service dropdown & custom input with form.service
watch(selectedServiceMode, (newVal) => {
    if (newVal !== 'Other') {
        form.service = newVal;
    } else {
        form.service = customService.value;
    }
});

watch(customService, (newVal) => {
    if (selectedServiceMode.value === 'Other') {
        form.service = newVal;
    }
});

// Sync category dropdown & custom input with form.category
watch(selectedCategoryMode, (newVal) => {
    if (newVal !== 'Other') {
        form.category = newVal;
    } else {
        form.category = customCategory.value;
    }
});

watch(customCategory, (newVal) => {
    if (selectedCategoryMode.value === 'Other') {
        form.category = newVal;
    }
});

// Toolbar formatting helpers
const insertFormat = (prefix, suffix = '') => {
    const textarea = textareaRef.value;
    if (!textarea) return;
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = form.description.substring(start, end) || 'text';
    const replacement = `${prefix}${selectedText}${suffix}`;
    form.description = form.description.substring(0, start) + replacement + form.description.substring(end);
};

const submit = () => {
    syncFormMedia();
    form.post(route('admin.projects.update', props.project.id), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head :title="`Edit Project: ${project.title}`" />

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        
        <!-- Header Row -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 pb-4 border-b border-gray-200 dark:border-gray-800">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Edit Project</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update details, gallery media, and settings for {{ project.title }}.</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <Link 
                    :href="route('admin.projects.index')" 
                    class="inline-flex items-center text-sm font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition"
                >
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Our Work
                </Link>
            </div>
        </div>

        <form @submit.prevent="submit">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Main Column (2 cols) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Card 1: Project Title -->
                    <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm">
                        <label for="title" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-2">
                            Project Title <span class="text-red-500">*</span>
                        </label>
                        <input 
                            id="title" 
                            v-model="form.title" 
                            type="text" 
                            placeholder="Enter project title..." 
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900/90 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-primary focus:border-transparent transition"
                            required 
                        />
                        <div v-if="form.errors.title" class="text-red-500 text-sm mt-1.5 font-medium">{{ form.errors.title }}</div>
                    </div>

                    <!-- Card 2: Industry, Service & Category Selection -->
                    <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm space-y-6">
                        
                        <!-- Industry -->
                        <div>
                            <label for="industry" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-2">
                                Industry
                            </label>
                            <input 
                                id="industry" 
                                v-model="form.industry" 
                                type="text" 
                                placeholder="e.g. Fintech, Healthcare, E-Commerce" 
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900/90 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-primary focus:border-transparent transition"
                            />
                            <!-- Quick Suggestions -->
                            <div class="flex flex-wrap gap-1.5 mt-2.5">
                                <span class="text-xs text-gray-400 font-semibold self-center mr-1">Suggestions:</span>
                                <button 
                                    v-for="ind in ['Fintech', 'Healthcare', 'E-Commerce', 'Education', 'Real Estate', 'Corporate']" 
                                    :key="ind" 
                                    type="button" 
                                    @click="form.industry = ind"
                                    class="text-[11px] px-2.5 py-1 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-600 dark:text-gray-300 hover:border-primary hover:text-primary transition"
                                >
                                    {{ ind }}
                                </button>
                            </div>
                            <div v-if="form.errors.industry" class="text-red-500 text-sm mt-1.5 font-medium">{{ form.errors.industry }}</div>
                        </div>

                        <!-- Service & Category Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            
                            <!-- Service Dropdown -->
                            <div>
                                <label for="service" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-2">
                                    Service <span class="text-xs text-gray-400 font-normal">(Primary Service)</span>
                                </label>
                                <select 
                                    id="service" 
                                    v-model="selectedServiceMode" 
                                    class="w-full px-4 py-3.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition shadow-xs cursor-pointer"
                                >
                                    <option value="" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-bold">Select Service...</option>
                                    <option v-for="opt in serviceOptions" :key="opt" :value="opt" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-bold">{{ opt }}</option>
                                </select>

                                <input 
                                    v-if="selectedServiceMode === 'Other'" 
                                    v-model="customService" 
                                    type="text" 
                                    placeholder="Type custom service..." 
                                    class="mt-2 w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900/90 border border-gray-300 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                />
                                <div v-if="form.errors.service" class="text-red-500 text-sm mt-1.5 font-medium">{{ form.errors.service }}</div>
                            </div>

                            <!-- Category Dropdown -->
                            <div>
                                <label for="category" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-2">
                                    Category <span class="text-xs text-gray-400 font-normal">(Classification)</span>
                                </label>
                                <select 
                                    id="category" 
                                    v-model="selectedCategoryMode" 
                                    class="w-full px-4 py-3.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition shadow-xs cursor-pointer"
                                >
                                    <option value="" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-bold">Select Category...</option>
                                    <option v-for="opt in categoryOptions" :key="opt" :value="opt" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-bold">{{ opt }}</option>
                                </select>

                                <input 
                                    v-if="selectedCategoryMode === 'Other'" 
                                    v-model="customCategory" 
                                    type="text" 
                                    placeholder="Type custom category..." 
                                    class="mt-2 w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900/90 border border-gray-300 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                />
                                <div v-if="form.errors.category" class="text-red-500 text-sm mt-1.5 font-medium">{{ form.errors.category }}</div>
                            </div>

                        </div>
                    </div>

                    <!-- Card 3: Extended Project Description -->
                    <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm">
                        <label for="description" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-2">
                            Project Description
                        </label>
                        
                        <!-- Rich Text Formatting Toolbar -->
                        <div class="flex flex-wrap items-center gap-1.5 p-2 bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-t-xl text-xs font-semibold text-gray-700 dark:text-gray-300">
                            <button type="button" @click="insertFormat('**', '**')" class="p-1.5 px-2.5 hover:bg-gray-200 dark:hover:bg-gray-800 rounded transition font-bold" title="Bold">B</button>
                            <button type="button" @click="insertFormat('*', '*')" class="p-1.5 px-2.5 hover:bg-gray-200 dark:hover:bg-gray-800 rounded transition italic" title="Italic">I</button>
                            <button type="button" @click="insertFormat('<u>', '</u>')" class="p-1.5 px-2.5 hover:bg-gray-200 dark:hover:bg-gray-800 rounded transition underline" title="Underline">U</button>
                            <button type="button" @click="insertFormat('~~', '~~')" class="p-1.5 px-2.5 hover:bg-gray-200 dark:hover:bg-gray-800 rounded transition line-through" title="Strikethrough">S</button>
                            <div class="h-4 w-px bg-gray-300 dark:bg-gray-700 mx-1"></div>
                            <button type="button" @click="insertFormat('# ')" class="p-1.5 px-2 hover:bg-gray-200 dark:hover:bg-gray-800 rounded transition" title="Heading 1">H1</button>
                            <button type="button" @click="insertFormat('## ')" class="p-1.5 px-2 hover:bg-gray-200 dark:hover:bg-gray-800 rounded transition" title="Heading 2">H2</button>
                            <button type="button" @click="insertFormat('### ')" class="p-1.5 px-2 hover:bg-gray-200 dark:hover:bg-gray-800 rounded transition" title="Heading 3">H3</button>
                            <div class="h-4 w-px bg-gray-300 dark:bg-gray-700 mx-1"></div>
                            <button type="button" @click="insertFormat('- ')" class="p-1.5 px-2 hover:bg-gray-200 dark:hover:bg-gray-800 rounded transition" title="Bullet List">• List</button>
                            <button type="button" @click="insertFormat('> ')" class="p-1.5 px-2 hover:bg-gray-200 dark:hover:bg-gray-800 rounded transition" title="Quote">“ Quote</button>
                            <button type="button" @click="insertFormat('[', '](https://)')" class="p-1.5 px-2 hover:bg-gray-200 dark:hover:bg-gray-800 rounded transition" title="Link">🔗 Link</button>
                        </div>

                        <!-- Taller Extended Textarea (rows 12, min-h 320px) -->
                        <textarea 
                            id="description" 
                            ref="textareaRef"
                            v-model="form.description" 
                            rows="12" 
                            placeholder="Describe the scope, objectives, architecture, technology stack, and key results of this project..." 
                            class="w-full p-4 min-h-[320px] bg-gray-50 dark:bg-gray-900/90 border border-t-0 border-gray-300 dark:border-gray-700 rounded-b-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-primary focus:border-transparent transition leading-relaxed"
                        ></textarea>

                        <div class="flex items-center justify-between mt-2 text-xs text-gray-400">
                            <span v-if="form.errors.description" class="text-red-500 font-medium">{{ form.errors.description }}</span>
                            <span class="ml-auto">{{ form.description ? form.description.length : 0 }} / 5000 chars</span>
                        </div>
                    </div>

                    <!-- Card 4: Multi-Media Gallery Attachments (Up to 15 items) -->
                    <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm space-y-6">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                            <div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    <span>Project Media Gallery</span>
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    Attach up to 10–15 images and videos via file uploads or external URLs.
                                </p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold" :class="totalMediaCount >= 15 ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300' : 'bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200'">
                                {{ totalMediaCount }} / 15 Items
                            </span>
                        </div>

                        <!-- Add Media Options Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            <!-- File Upload Area -->
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-4 text-center bg-gray-50 dark:bg-gray-900/50 hover:border-primary transition">
                                <input 
                                    ref="galleryFileInput"
                                    type="file" 
                                    accept="image/*,video/*"
                                    multiple 
                                    class="hidden" 
                                    @change="handleGalleryFiles"
                                />
                                <div class="flex flex-col items-center">
                                    <svg class="w-8 h-8 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <button 
                                        type="button"
                                        @click="triggerGalleryFileInput"
                                        :disabled="!canAddMore"
                                        class="px-3 py-1.5 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary-dark transition disabled:opacity-50"
                                    >
                                        📁 Upload Image / Video Files
                                    </button>
                                    <span class="text-[10px] text-gray-400 mt-1">Select multiple PNG, JPG, WebP, MP4, WebM</span>
                                </div>
                            </div>

                            <!-- External URL Link Adder -->
                            <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 bg-gray-50 dark:bg-gray-900/50 space-y-2">
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300 block">🔗 Add Media URL Link</span>
                                <div class="flex items-center space-x-2">
                                    <select v-model="newUrlType" class="px-2.5 py-1.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-xs font-semibold text-gray-800 dark:text-white">
                                        <option value="image">Image URL</option>
                                        <option value="video">Video URL</option>
                                    </select>
                                    <input 
                                        v-model="newUrlInput"
                                        type="url"
                                        placeholder="https://example.com/media.mp4"
                                        class="flex-1 px-3 py-1.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-xs text-gray-900 dark:text-white"
                                    />
                                </div>
                                <button 
                                    type="button"
                                    @click="addMediaUrl"
                                    :disabled="!canAddMore || !newUrlInput.trim()"
                                    class="w-full py-1.5 bg-gray-800 text-white dark:bg-gray-700 text-xs font-bold rounded-lg hover:bg-black transition disabled:opacity-50"
                                >
                                    + Attach Media Link
                                </button>
                            </div>

                        </div>

                        <!-- Gallery Items Grid -->
                        <div v-if="galleryItems.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 pt-2">
                            <div 
                                v-for="(item, idx) in galleryItems" 
                                :key="item.id"
                                class="relative group rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-900 h-28 flex items-center justify-center shadow-xs"
                            >
                                <!-- Image Preview -->
                                <template v-if="item.type === 'image'">
                                    <img :src="item.previewUrl" class="w-full h-full object-cover" :alt="'Media ' + idx" />
                                </template>

                                <!-- Video Preview -->
                                <template v-else>
                                    <video v-if="item.source === 'file'" :src="item.previewUrl" class="w-full h-full object-cover"></video>
                                    <div v-else class="w-full h-full flex flex-col items-center justify-center p-2 text-center text-white bg-indigo-950">
                                        <svg class="w-6 h-6 text-secondary-200 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        </svg>
                                        <span class="text-[9px] truncate max-w-full px-1">{{ item.url }}</span>
                                    </div>
                                </template>

                                <!-- Badges Overlay -->
                                <div class="absolute top-1.5 left-1.5 flex items-center space-x-1">
                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold text-white uppercase bg-black/60 backdrop-blur-xs">
                                        {{ item.type === 'video' ? '🎬 Video' : '🖼️ Image' }}
                                    </span>
                                </div>

                                <!-- Source Badge Overlay -->
                                <div class="absolute bottom-1.5 left-1.5">
                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold text-white bg-black/60 backdrop-blur-xs">
                                        {{ item.source === 'file' ? '📁 File' : '🔗 Link' }}
                                    </span>
                                </div>

                                <!-- Remove Action Overlay -->
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <button 
                                        type="button" 
                                        @click="removeGalleryItem(idx)"
                                        class="px-2.5 py-1 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-lg transition"
                                    >
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Card 5: Demo URL -->
                    <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm">
                        <label for="demo_url" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-2">
                            Demo URL (Optional)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                            </div>
                            <input 
                                id="demo_url" 
                                v-model="form.demo_url" 
                                type="url" 
                                placeholder="https://example.com" 
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-gray-900/90 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-primary focus:border-transparent transition"
                            />
                        </div>
                        <div v-if="form.errors.demo_url" class="text-red-500 text-sm mt-1.5 font-medium">{{ form.errors.demo_url }}</div>
                    </div>

                    <!-- SEO & Social Media Metadata (Open Graph) Section -->
                    <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
                        <div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200">
                                🔍 SEO & Social Media Link Previews (Open Graph)
                            </span>
                            <h3 class="text-base font-extrabold text-gray-900 dark:text-white mt-2">
                                Search Engine & Social Media Optimization
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Customize how this project post appears when shared on WhatsApp, Facebook, X (Twitter), LinkedIn, and Google Search.
                            </p>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="meta_title" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                    Custom SEO Meta Title
                                </label>
                                <input 
                                    v-model="form.meta_title" 
                                    id="meta_title" 
                                    type="text" 
                                    placeholder="e.g. Enterprise E-Commerce System Case Study | Skynet Digital"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                />
                                <span class="text-[10px] text-gray-400 mt-1 block">Leave blank to automatically use the main Project Title.</span>
                            </div>

                            <div>
                                <label for="meta_description" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                    Custom Meta Description (Social Media Card Summary)
                                </label>
                                <textarea 
                                    v-model="form.meta_description" 
                                    id="meta_description" 
                                    rows="3" 
                                    placeholder="A brief 1-2 sentence description shown on Facebook, WhatsApp, and Google search previews..."
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                ></textarea>
                                <span class="text-[10px] text-gray-400 mt-1 block">Leave blank to automatically extract from the main Project Description.</span>
                            </div>

                            <div>
                                <label for="meta_keywords" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                    SEO Meta Keywords
                                </label>
                                <input 
                                    v-model="form.meta_keywords" 
                                    id="meta_keywords" 
                                    type="text" 
                                    placeholder="e.g. e-commerce, web application, software engineering, Delta State"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                />
                                <span class="text-[10px] text-gray-400 mt-1 block">Comma-separated tags for search engines.</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Sidebar Column (1 col) -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Card 1: Update Settings -->
                    <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-gray-200 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">
                            Update Settings
                        </h3>

                        <div class="space-y-4 mb-6">
                            <div>
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-2">Status</label>
                                <div class="flex flex-col space-y-2.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <label class="inline-flex items-center cursor-pointer p-2.5 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900 transition" :class="form.is_published ? 'border-primary dark:border-secondary-200 bg-primary/5 dark:bg-secondary-200/10' : ''">
                                        <input type="radio" :value="true" v-model="form.is_published" name="is_published" class="text-primary focus:ring-primary h-4 w-4" />
                                        <span class="ml-2.5 font-semibold text-green-600 dark:text-green-400">Published</span>
                                        <span class="ml-auto text-[10px] bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 px-2 py-0.5 rounded-full uppercase">Visible</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer p-2.5 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900 transition" :class="!form.is_published ? 'border-amber-500 bg-amber-50 dark:bg-amber-950/20' : ''">
                                        <input type="radio" :value="false" v-model="form.is_published" name="is_published" class="text-primary focus:ring-primary h-4 w-4" />
                                        <span class="ml-2.5 font-semibold text-amber-600 dark:text-amber-400">Draft / Unpublished</span>
                                        <span class="ml-auto text-[10px] bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300 px-2 py-0.5 rounded-full uppercase">Hidden</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button 
                            type="submit" 
                            :disabled="form.processing" 
                            class="w-full py-3.5 px-4 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl shadow-md hover:shadow-lg transition transform active:scale-98 disabled:opacity-50 flex items-center justify-center text-sm"
                        >
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>{{ form.processing ? 'Updating...' : 'Update Project' }}</span>
                        </button>
                    </div>

                    <!-- Card: Assign Owner (Super Admin Only) -->
                    <div v-if="isSuperAdmin" class="bg-white dark:bg-gray-800/90 border border-indigo-200 dark:border-indigo-700/60 rounded-2xl p-6 shadow-sm">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-gray-200 uppercase tracking-wider mb-1 pb-2 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                            <span class="text-indigo-500">👑</span> Assign Owner
                        </h3>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 mb-4">Reassign this project post to a different staff member or administrator.</p>

                        <!-- Current owner preview -->
                        <div v-if="selectedOwner" class="flex items-center gap-3 mb-4 p-3 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/50">
                            <div class="w-9 h-9 rounded-full flex-shrink-0 flex items-center justify-center font-extrabold text-sm text-white shadow-sm" style="background: linear-gradient(135deg, #6366f1, #8b5cf6)">
                                {{ getInitials(selectedOwner.name) }}
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ selectedOwner.name }}</div>
                                <div class="text-[10px] text-gray-400 capitalize">{{ selectedOwner.role }} &bull; {{ selectedOwner.email }}</div>
                            </div>
                        </div>

                        <div v-else class="flex items-center gap-2 mb-4 text-[11px] text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 rounded-xl px-3 py-2">
                            ⚠️ No owner assigned (legacy post)
                        </div>

                        <label for="user_id" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider block mb-1.5">Select New Owner</label>
                        <select
                            id="user_id"
                            v-model="form.user_id"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        >
                            <option :value="null">— Unassigned —</option>
                            <option v-for="u in users" :key="u.id" :value="u.id">
                                {{ u.name }} ({{ u.role }})
                            </option>
                        </select>
                        <div v-if="form.errors.user_id" class="text-red-500 text-xs mt-1 font-medium">{{ form.errors.user_id }}</div>
                    </div>

                    <!-- Card 2: Cover Image Upload -->
                    <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-gray-200 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">
                            Cover Image
                        </h3>

                        <input 
                            ref="fileInput" 
                            type="file" 
                            accept="image/*" 
                            class="hidden" 
                            @change="handleImageChange" 
                        />

                        <div 
                            @click="triggerFileInput"
                            class="border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-primary dark:hover:border-primary rounded-2xl p-6 text-center cursor-pointer transition flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-900/50 min-h-[180px] group"
                        >
                            <template v-if="imagePreview">
                                <div class="relative w-full h-40 rounded-xl overflow-hidden shadow group">
                                    <img :src="imagePreview" alt="Cover preview" class="w-full h-full object-cover" />
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center space-x-2">
                                        <span class="text-xs font-bold text-white bg-primary px-3 py-1.5 rounded-lg">Change</span>
                                        <button type="button" @click.stop="removeImage" class="text-xs font-bold text-white bg-red-600 px-3 py-1.5 rounded-lg hover:bg-red-700">Remove</button>
                                    </div>
                                </div>
                            </template>

                            <template v-else>
                                <div class="w-12 h-12 rounded-full bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Click to upload Image</p>
                                <p class="text-xs text-gray-400 mt-1">PNG, JPG, WebP up to 2MB</p>
                            </template>
                        </div>

                        <div v-if="form.errors.image_path" class="text-red-500 text-sm mt-2 font-medium text-center">{{ form.errors.image_path }}</div>
                    </div>

                </div>

            </div>
        </form>
    </div>
</template>
