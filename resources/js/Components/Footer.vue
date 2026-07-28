<script setup>
import Logo from '../../images/logo.png';
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <footer class="relative pb-12 pt-16 bg-gray-100 text-gray-800 dark:bg-gray-950 dark:text-gray-100 border-t border-gray-200 dark:border-gray-800">
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

        <!-- Left Column: Logo + Company Description + RC Registration Badge -->
        <div class="space-y-4">
          <div class="flex items-center space-x-3">
            <img :src="Logo" alt="Skynet Digital Limited Logo" class="h-12 w-auto" />
            <div>
              <h3 class="font-black text-lg text-primary dark:text-secondary-200 leading-none">
                SKYNET DIGITAL
              </h3>
              <span class="text-[10px] text-gray-500 dark:text-gray-400 font-bold tracking-widest block uppercase mt-0.5">
                Limited
              </span>
            </div>
          </div>

          <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
            We design, build, secure, and maintain modern digital systems that help organizations grow, operate faster, and stay competitive.
          </p>

          <!-- CAC Registration Badge -->
          <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20 text-xs font-bold">
            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span>CAC Registered Entity • RC No. 9168845</span>
          </div>
        </div>

        <!-- Middle Column: Navigation Links -->
        <nav class="space-y-3" aria-label="Site Links">
          <h4 class="font-bold text-base text-gray-900 dark:text-white uppercase tracking-wider">Quick Navigation</h4>
          <ul class="space-y-2 text-xs font-medium text-gray-600 dark:text-gray-300">
            <li><Link :href="route('home')" class="hover:text-primary dark:hover:text-secondary-200 transition">Home</Link></li>
            <li><Link :href="route('about')" class="hover:text-primary dark:hover:text-secondary-200 transition">About Us</Link></li>
            <li><Link :href="route('services')" class="hover:text-primary dark:hover:text-secondary-200 transition">Services & Capabilities</Link></li>
            <li><Link :href="route('our-work')" class="hover:text-primary dark:hover:text-secondary-200 transition">Our Work & Case Studies</Link></li>
            <li><Link :href="route('testimonials')" class="hover:text-primary dark:hover:text-secondary-200 transition">Client Testimonials & Ratings</Link></li>
            <li><Link :href="route('contact')" class="hover:text-primary dark:hover:text-secondary-200 transition">Contact & Consultation</Link></li>
          </ul>
        </nav>

        <!-- Right Column: Official Contact Info -->
        <address class="not-italic space-y-3 text-xs text-gray-600 dark:text-gray-300">
          <h4 class="font-bold text-base text-gray-900 dark:text-white uppercase tracking-wider">Official Contacts</h4>
          <div class="space-y-2">
            <div class="flex items-center">
              <span class="font-bold text-gray-400 mr-2">Location:</span>
              <span>Delta State, Nigeria</span>
            </div>
            <div class="flex items-center">
              <span class="font-bold text-gray-400 mr-2">Phone:</span>
              <a href="tel:+2348032072831" class="hover:text-primary transition font-semibold">+234 803 207 2831</a>
            </div>
            <div class="flex items-center">
              <span class="font-bold text-gray-400 mr-2">Email:</span>
              <a href="mailto:support@skynetdigitalltd.com" class="hover:text-primary transition font-semibold">support@skynetdigitalltd.com</a>
            </div>
            <div class="flex items-center">
              <span class="font-bold text-gray-400 mr-2">RC Number:</span>
              <span class="font-bold text-primary dark:text-secondary-200">RC 9168845</span>
            </div>
          </div>
        </address>

      </div>

      <!-- Divider -->
      <hr class="my-8 border-t border-gray-200 dark:border-gray-800" />

      <!-- Footer Copyright Row -->
      <div class="flex flex-col md:flex-row justify-between items-center text-xs text-gray-500 dark:text-gray-400 gap-4">
        <div>
          © {{ new Date().getFullYear() }} <span class="font-semibold text-gray-700 dark:text-gray-300">Skynet Digital Limited</span>. All rights reserved. <span class="font-bold text-gray-500">(RC No. 9168845)</span>
        </div>
        <div class="flex space-x-4">
          <a href="#" class="hover:text-primary dark:hover:text-white transition">Privacy Policy</a>
          <a href="#" class="hover:text-primary dark:hover:text-white transition">Terms of Service</a>
        </div>
      </div>
    </div>
  </footer>
</template>
