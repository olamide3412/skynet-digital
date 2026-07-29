<script setup>
import { ref } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import Logo from '../../../images/logo.png';
import NavLink from '@/Components/Navigations/NavLink.vue';
import ThemeToggle from '@/Components/ThemeToggle.vue';

const page = usePage();
const isOpen = ref(false);
const toggle = () => (isOpen.value = !isOpen.value);
</script>

<template>
  <nav class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md border-b border-gray-200/80 dark:border-gray-800/80 shadow-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16 sm:h-20">
        
        <!-- Brand Logo & Title -->
        <Link href="/" class="flex items-center space-x-3 group">
          <!-- Glassmorphic Emblem Container -->
          <div class="p-1 sm:p-1.5 rounded-2xl bg-gradient-to-tr from-primary/10 via-purple-500/10 to-blue-500/10 border border-primary/20 dark:border-secondary/30 shadow-xs group-hover:scale-105 transition-transform duration-300 flex items-center justify-center">
            <img :src="Logo" class="h-9 sm:h-10 w-auto object-contain" alt="Skynet Digital Logo" />
          </div>
          <div class="flex flex-col">
            <span class="text-lg sm:text-xl font-black tracking-tight leading-none bg-gradient-to-r from-primary via-indigo-600 to-purple-600 dark:from-secondary-200 dark:via-blue-300 dark:to-purple-300 bg-clip-text text-transparent group-hover:opacity-90 transition-opacity">
              SKYNET DIGITAL
            </span>
            <span class="text-[9px] sm:text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-[0.15em] leading-tight mt-1 flex items-center gap-1">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
              LIMITED • RC 9168845
            </span>
          </div>
        </Link>

        <!-- Desktop Navigation Items -->
        <ul class="hidden md:flex items-center space-x-1 lg:space-x-2 list-none p-0 m-0">
          <NavLink :href="route('home')" :active="$page.component === 'Home'">Home</NavLink>
          <NavLink :href="route('about')" :active="$page.component === 'About'">About</NavLink>
          <NavLink :href="route('services')" :active="$page.component === 'Services'">Services</NavLink>
          <NavLink :href="route('our-work')" :active="$page.component === 'OurWork' || $page.component.startsWith('OurWork/')">Our Work</NavLink>
          <NavLink :href="route('testimonials')" :active="$page.component === 'Testimonials'">Testimonials</NavLink>
          <NavLink :href="route('contact')" :active="$page.component === 'Contact'">Contact</NavLink>
        </ul>

        <!-- Desktop Right Actions: Admin Panel / Login & Theme Toggle -->
        <div class="hidden md:flex items-center space-x-3">
          
          <template v-if="$page.props.auth.user">
            <Link 
              :href="route('dashboard')" 
              class="px-4 py-2 bg-gradient-to-r from-primary to-indigo-600 hover:from-primary-dark hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-md hover:shadow-lg transition transform active:scale-95 flex items-center"
            >
              <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
              </svg>
              Admin Panel
            </Link>

            <Link 
              :href="route('logout')" 
              method="post" 
              as="button" 
              class="text-xs font-bold text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 px-2 py-1 transition"
            >
              Logout
            </Link>
          </template>

          <template v-else>
            <Link 
              :href="route('login')" 
              class="px-4 py-2 border border-gray-300 dark:border-gray-700 hover:border-primary text-gray-800 dark:text-gray-200 hover:text-primary dark:hover:text-white rounded-xl text-xs font-bold transition"
            >
              Login
            </Link>
          </template>

          <div class="pl-2 border-l border-gray-200 dark:border-gray-800">
            <ThemeToggle />
          </div>
        </div>

        <!-- Mobile Controls Toggle -->
        <div class="flex items-center space-x-2 md:hidden">
          <ThemeToggle />
          <button 
            @click="toggle" 
            class="p-2 rounded-xl text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none transition"
            aria-label="Toggle Navigation Drawer"
          >
            <svg v-if="!isOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

      </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <transition name="slide">
      <div v-if="isOpen" class="md:hidden bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl border-b border-gray-200 dark:border-gray-800 px-6 py-6 space-y-4 shadow-xl">
        <div class="flex flex-col space-y-3 font-semibold text-sm text-gray-800 dark:text-gray-200">
          <Link :href="route('home')" @click="toggle" class="py-2 border-b border-gray-100 dark:border-gray-800" :class="$page.component === 'Home' ? 'text-primary font-bold' : ''">Home</Link>
          <Link :href="route('about')" @click="toggle" class="py-2 border-b border-gray-100 dark:border-gray-800" :class="$page.component === 'About' ? 'text-primary font-bold' : ''">About Us</Link>
          <Link :href="route('services')" @click="toggle" class="py-2 border-b border-gray-100 dark:border-gray-800" :class="$page.component === 'Services' ? 'text-primary font-bold' : ''">Services</Link>
          <Link :href="route('our-work')" @click="toggle" class="py-2 border-b border-gray-100 dark:border-gray-800" :class="$page.component === 'OurWork' || $page.component.startsWith('OurWork/') ? 'text-primary font-bold' : ''">Our Work</Link>
          <Link :href="route('testimonials')" @click="toggle" class="py-2 border-b border-gray-100 dark:border-gray-800" :class="$page.component === 'Testimonials' ? 'text-primary font-bold' : ''">Testimonials</Link>
          <Link :href="route('contact')" @click="toggle" class="py-2" :class="$page.component === 'Contact' ? 'text-primary font-bold' : ''">Contact</Link>
        </div>

        <div class="pt-4 border-t border-gray-200 dark:border-gray-800 space-y-2">
          <template v-if="$page.props.auth.user">
            <Link :href="route('dashboard')" @click="toggle" class="w-full py-3 text-center bg-gradient-to-r from-primary to-indigo-600 text-white font-bold rounded-xl block shadow-sm">
              Admin Panel
            </Link>
            <Link :href="route('logout')" method="post" as="button" @click="toggle" class="w-full py-2.5 text-center text-red-600 dark:text-red-400 font-bold block">
              Logout
            </Link>
          </template>
          <template v-else>
            <Link :href="route('login')" @click="toggle" class="w-full py-3 text-center border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-200 font-bold rounded-xl block">
              Login
            </Link>
          </template>
        </div>
      </div>
    </transition>
  </nav>
</template>

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease-in-out;
}
.slide-enter-from,
.slide-leave-to {
  transform: translateY(-10px);
  opacity: 0;
}
</style>
