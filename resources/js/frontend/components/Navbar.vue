<template>
    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
         :class="[isScrolled || !isHomePage ? 'bg-white dark:bg-gray-900 shadow-lg' : 'bg-transparent']">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo from API Settings -->
                <router-link to="/" class="flex items-center space-x-3">
                    <template v-if="currentLogo">
                        <img 
                            :src="currentLogo" 
                            :alt="displayName"
                            class="h-12 w-auto object-contain"
                        >
                    </template>
                    <template v-else>
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-xl">{{ displayName.charAt(0) }}</span>
                        </div>
                    </template>

                </router-link>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-8">
                    <template v-for="link in navLinks" :key="link.to">
                        <!-- Campuses Dropdown -->
                        <div v-if="link.key === 'campuses'" class="relative group h-full flex items-center">
                            <router-link 
                                :to="link.to"
                                class="flex items-center space-x-1 font-medium transition-colors duration-200 hover:text-amber-500 py-2"
                                :class="[isScrolled || !isHomePage ? 'text-gray-700 dark:text-gray-200' : 'text-white']"
                                active-class="!text-amber-500"
                            >
                                <span>{{ link.label }}</span>
                                <svg class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </router-link>

                            <!-- Dropdown with CSS Hover -->
                            <div class="absolute top-full left-0 mt-2 w-72 bg-white dark:bg-gray-800 rounded-xl shadow-xl py-2 border border-gray-100 dark:border-gray-700 z-50 invisible opacity-0 translate-y-2 group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 transform origin-top-left">
                                <div class="max-h-[70vh] overflow-y-auto custom-scrollbar">
                                    <div v-if="campuses.length === 0" class="px-4 py-4 text-center text-gray-500 text-sm">
                                        <div class="animate-spin inline-block w-4 h-4 border-2 border-amber-500 border-t-transparent rounded-full mb-2"></div>
                                        <p>Loading campuses...</p>
                                    </div>
                                    
                                    <router-link 
                                        v-else
                                        v-for="campus in campuses" 
                                        :key="campus.id"
                                        :to="{ name: 'CampusDetail', params: { slug: campus.slug || 'campus-' + campus.id } }"
                                        class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-amber-50 dark:hover:bg-amber-900/30 hover:text-amber-600 dark:hover:text-amber-400 transition-colors border-b border-gray-50 dark:border-gray-700/50 last:border-0"
                                    >
                                        <div class="w-8 h-8 rounded-lg overflow-hidden shrink-0 mr-3 border border-gray-100">
                                            <img :src="campus.image" :alt="campus.name" class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="truncate font-bold text-xs uppercase">{{ campus.code }}</p>
                                            <p class="truncate font-medium">{{ campus.name }}</p>
                                        </div>
                                    </router-link>
                                </div>
                                <div class="border-t border-gray-100 dark:border-gray-700 mt-1 pt-2 px-2 pb-1">
                                    <router-link 
                                        to="/campuses"
                                        class="flex items-center justify-center py-2 text-[10px] font-bold text-amber-600 uppercase tracking-widest hover:bg-amber-50 rounded-lg transition-colors"
                                    >
                                        View All Campuses
                                    </router-link>
                                </div>
                            </div>
                        </div>

                        <!-- Regular Link -->
                        <router-link 
                            v-else
                            :to="link.to"
                            class="font-medium transition-colors duration-200 hover:text-amber-500"
                            :class="[isScrolled || !isHomePage ? 'text-gray-700 dark:text-gray-200' : 'text-white']"
                            active-class="!text-amber-500"
                        >
                            {{ link.label }}
                        </router-link>
                    </template>
                </div>

                <!-- Right Side: Language + Auth -->
                <div class="hidden lg:flex items-center space-x-4">
                    <!-- Dark Mode Toggle -->
                    <button 
                        @click="toggleDarkMode"
                        class="p-2 rounded-lg transition-all duration-200"
                        :class="[isScrolled || !isHomePage ? 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' : 'text-white hover:bg-white/10']"
                    >
                        <i v-if="isDarkMode" class="fas fa-sun text-yellow-400"></i>
                        <i v-else class="fas fa-moon"></i>
                    </button>

                    <!-- Language Switcher -->
                    <div class="relative" ref="langMenuRef">
                        <button 
                            @click="showLangMenu = !showLangMenu"
                            class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-all duration-200"
                            :class="[isScrolled || !isHomePage ? 'bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200' : 'bg-white/10 hover:bg-white/20 text-white']"
                        >
                            <span class="text-lg">{{ currentLang.flag }}</span>
                            <span class="text-sm font-medium">{{ currentLang.code }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Language Dropdown -->
                        <transition name="dropdown">
                            <div v-if="showLangMenu" class="absolute right-0 mt-2 w-44 bg-white dark:bg-gray-800 rounded-xl shadow-xl py-2 border border-gray-100 dark:border-gray-700 overflow-hidden">
                                <button 
                                    v-for="lang in languages" 
                                    :key="lang.code"
                                    @click="switchLanguage(lang)"
                                    class="flex items-center w-full px-4 py-2.5 text-gray-700 dark:text-gray-200 hover:bg-amber-50 dark:hover:bg-amber-900/30 hover:text-amber-600 dark:hover:text-amber-400 transition-colors"
                                    :class="{ 'bg-amber-50 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400': currentLang.code === lang.code }"
                                >
                                    <span class="text-xl mr-3">{{ lang.flag }}</span>
                                    <span class="font-medium">{{ lang.name }}</span>
                                    <svg v-if="currentLang.code === lang.code" class="w-4 h-4 ml-auto text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        </transition>
                    </div>

                    <!-- Auth Buttons / User Menu -->
                    <template v-if="!user">
                        <router-link 
                            to="/login"
                            class="font-medium transition-colors duration-200 hover:text-amber-500"
                            :class="[isScrolled || !isHomePage ? 'text-gray-700' : 'text-white']"
                        >
                            {{ t('Sign In') }}
                        </router-link>
                        <router-link 
                            to="/register"
                            class="px-5 py-2.5 bg-amber-500 text-white font-medium rounded-full hover:bg-amber-600 transition-all duration-200 shadow-lg hover:shadow-xl"
                        >
                            {{ t('Register') }}
                        </router-link>
                    </template>
                    <template v-else>
                        <div class="relative" ref="userMenuRef">
                            <button 
                                @click="showUserMenu = !showUserMenu"
                                class="flex items-center space-x-2 px-3 py-2 rounded-full transition-colors duration-200"
                                :class="[isScrolled || !isHomePage ? 'bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700' : 'bg-white/20 hover:bg-white/30']"
                            >
                                <img :src="userAvatar" class="w-8 h-8 rounded-full object-cover" alt="Avatar">
                                <span class="font-medium" :class="[isScrolled || !isHomePage ? 'text-gray-700 dark:text-gray-200' : 'text-white']">{{ user.name }}</span>
                                <svg class="w-4 h-4" :class="[isScrolled || !isHomePage ? 'text-gray-500 dark:text-gray-400' : 'text-white']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <transition name="dropdown">
                                <div v-if="showUserMenu" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-xl py-2 border border-gray-100 dark:border-gray-700">
                                    <a href="/dashboard" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-amber-50 dark:hover:bg-amber-900/30 hover:text-amber-600 dark:hover:text-amber-400">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        {{ t('Dashboard') }}
                                    </a>
                                    <a href="/dashboard" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-amber-50 dark:hover:bg-amber-900/30 hover:text-amber-600 dark:hover:text-amber-400">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        {{ t('My Classes') }}
                                    </a>
                                    <a href="/dashboard" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-amber-50 dark:hover:bg-amber-900/30 hover:text-amber-600 dark:hover:text-amber-400">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                        {{ t('Attendance') }}
                                    </a>
                                    <a href="/dashboard" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-amber-50 dark:hover:bg-amber-900/30 hover:text-amber-600 dark:hover:text-amber-400">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        {{ t('Results') }}
                                    </a>
                                    <a href="/profile" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-amber-50 dark:hover:bg-amber-900/30 hover:text-amber-600 dark:hover:text-amber-400">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ t('Profile') }}
                                    </a>
                                    <hr class="my-2 border-gray-100 dark:border-gray-700">
                                    <button @click="logout" class="flex items-center w-full px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        {{ t('Logout') }}
                                    </button>
                                </div>
                            </transition>
                        </div>
                    </template>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center space-x-3 lg:hidden">
                    <!-- Mobile Language Button -->
                    <button 
                        @click="showLangMenu = !showLangMenu"
                        class="p-2 rounded-lg"
                        :class="[isScrolled || !isHomePage ? 'text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-800' : 'text-white bg-white/10']"
                    >
                        <span class="text-lg">{{ currentLang.flag }}</span>
                    </button>
                    
                    <!-- Mobile Menu Toggle -->
                    <button 
                        @click="showMobileMenu = !showMobileMenu"
                        class="p-2 rounded-lg"
                        :class="[isScrolled || !isHomePage ? 'text-gray-700' : 'text-white']"
                    >
                        <svg v-if="!showMobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Language Menu -->
        <transition name="dropdown">
            <div v-if="showLangMenu" class="lg:hidden absolute right-4 top-20 w-44 bg-white dark:bg-gray-800 rounded-xl shadow-xl py-2 border border-gray-100 dark:border-gray-700 overflow-hidden">
                <button 
                    v-for="lang in languages" 
                    :key="lang.code"
                    @click="switchLanguage(lang)"
                    class="flex items-center w-full px-4 py-2.5 text-gray-700 dark:text-gray-200 hover:bg-amber-50 dark:hover:bg-amber-900/40 hover:text-amber-600 dark:hover:text-amber-400"
                    :class="{ 'bg-amber-50 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400': currentLang.code === lang.code }"
                >
                    <span class="text-xl mr-3">{{ lang.flag }}</span>
                    <span class="font-medium">{{ lang.name }}</span>
                </button>
            </div>
        </transition>

        <!-- Mobile Menu -->
        <transition name="slide-down">
            <div v-if="showMobileMenu" class="lg:hidden bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 shadow-xl">
                <div class="px-4 py-6 space-y-3">
                    <router-link 
                        v-for="link in navLinks" 
                        :key="link.to"
                        :to="link.to"
                        @click="showMobileMenu = false"
                        class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 font-medium rounded-lg hover:bg-amber-50 dark:hover:bg-amber-900/30 hover:text-amber-600 dark:hover:text-amber-400 transition-colors"
                        active-class="!bg-amber-50 !dark:bg-amber-900/40 !text-amber-600 !dark:text-amber-400"
                    >
                        <i v-if="link.key === 'campuses'" class="fas fa-university mr-3 text-amber-500 w-5"></i>
                        <i v-else-if="link.key === 'home'" class="fas fa-home mr-3 text-amber-500 w-5"></i>
                        <i v-else-if="link.key === 'programs'" class="fas fa-graduation-cap mr-3 text-amber-500 w-5"></i>
                        <i v-else-if="link.key === 'blog'" class="fas fa-newspaper mr-3 text-amber-500 w-5"></i>
                        <i v-else-if="link.key === 'about'" class="fas fa-info-circle mr-3 text-amber-500 w-5"></i>
                        <i v-else-if="link.key === 'contact'" class="fas fa-phone mr-3 text-amber-500 w-5"></i>
                        {{ link.label }}
                    </router-link>
                    <hr class="border-gray-200 dark:border-gray-700">
                    <template v-if="!user">
                        <router-link 
                            to="/login"
                            @click="showMobileMenu = false"
                            class="block px-4 py-3 text-gray-700 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800"
                        >
                            {{ t('Sign In') }}
                        </router-link>
                        <router-link 
                            to="/register"
                            @click="showMobileMenu = false"
                            class="block px-4 py-3 bg-amber-500 text-white font-medium rounded-lg text-center shadow-lg"
                        >
                            {{ t('Register') }}
                        </router-link>
                    </template>
                    <template v-else>
                        <a href="/dashboard" class="block px-4 py-3 text-gray-700 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800">
                            {{ t('Dashboard') }}
                        </a>
                        <a href="/student/classes" class="block px-4 py-3 text-gray-700 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800">
                            {{ t('My Classes') }}
                        </a>
                        <a href="/profile" class="block px-4 py-3 text-gray-700 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800">
                            {{ t('Profile') }}
                        </a>
                        <button @click="logout" class="block w-full text-left px-4 py-3 text-red-600 font-medium rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20">
                            {{ t('Logout') }}
                        </button>
                    </template>
                </div>
            </div>
        </transition>
    </nav>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRoute } from 'vue-router';
import { onClickOutside } from '@vueuse/core';
import { t, setLocale, translationKey } from '../composables/useTranslation';

const route = useRoute();
const isScrolled = ref(false);
const showMobileMenu = ref(false);
const showUserMenu = ref(false);
const showLangMenu = ref(false);
const showCampusesMenu = ref(false);
const userMenuRef = ref(null);
const langMenuRef = ref(null);
const campusesMenuRef = ref(null);

// Get user data from window (set by Laravel)
const user = ref(window.authUser || null);
const universityName = ref(window.universitySettings?.name || 'Sourng Academy');
const universityLogo = ref(null);
const universityShortName = ref('');
const logoDark = ref(null);
const logoBright = ref(null);
const isDarkMode = ref(false);
const campuses = ref([]);

const displayName = computed(() => universityShortName.value || universityName.value);

const currentLogo = computed(() => {
    if (isDarkMode.value) {
        return logoBright.value || universityLogo.value;
    } else {
        return (isScrolled.value || !isHomePage.value) ? (logoDark.value || universityLogo.value) : (logoBright.value || universityLogo.value);
    }
});

const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    updateTheme();
};

const updateTheme = () => {
    if (isDarkMode.value) {
        document.documentElement.classList.add('dark-mode');
        document.body.classList.add('dark-mode');
        localStorage.setItem('darkMode', 'enabled');
    } else {
        document.documentElement.classList.remove('dark-mode');
        document.body.classList.remove('dark-mode');
        localStorage.setItem('darkMode', 'disabled');
    }
};

// Language settings
const languages = [
    { code: 'EN', name: 'English', flag: '🇺🇸', locale: 'en' },
    { code: 'KM', name: 'ភាសាខ្មែរ', flag: '🇰🇭', locale: 'km' },
    { code: 'ZH', name: '中文', flag: '🇨🇳', locale: 'zh' },
    { code: 'FR', name: 'Français', flag: '🇫🇷', locale: 'fr' }
];

// Get current language from localStorage or default to English
const savedLang = localStorage.getItem('frontend_locale') || 'en';
const currentLang = ref(languages.find(l => l.locale === savedLang) || languages[0]);

const switchLanguage = async (lang) => {
    currentLang.value = lang;
    localStorage.setItem('frontend_locale', lang.locale);
    showLangMenu.value = false;
    
    // Update translations
    await setLocale(lang.locale);
};

// Fetch university settings from API
const fetchSettings = async () => {
    try {
        const response = await fetch('/api/frontend/settings');
        const data = await response.json();
        if (data.success && data.data) {
            universityName.value = data.data.university_name || data.data.hotel_name || 'Sourng Academy';
            universityShortName.value = data.data.university_short_name || '';
            universityLogo.value = data.data.logo || null;
            logoDark.value = data.data.logo_dark || null;
            logoBright.value = data.data.logo_bright || null;
        }
    } catch (error) {
        console.log('Using default university settings');
    }
};

const fetchCampuses = async () => {
    try {
        const response = await axios.get('/api/frontend/campuses');
        if (response.data.success) {
            campuses.value = response.data.data;
        }
    } catch (error) {
        console.error('Error fetching campuses:', error);
    }
};

const isHomePage = computed(() => route.path === '/');

// Generate avatar URL
const userAvatar = computed(() => {
    if (user.value?.avatar) {
        return user.value.avatar;
    }
    const name = encodeURIComponent(user.value?.name || 'User');
    return `https://ui-avatars.com/api/?name=${name}&background=f59e0b&color=fff&size=128`;
});

// Navigation links with translation keys
const navLinks = computed(() => [
    { to: '/', label: t('Home'), key: 'home' },
    { to: '/campuses', label: t('Campuses'), key: 'campuses' },
    { to: '/programs', label: t('Programs'), key: 'programs' },
    { to: '/blog', label: t('News'), key: 'blog' },
    { to: '/about', label: t('About'), key: 'about' },
    { to: '/contact', label: t('Contact'), key: 'contact' },
]);

// Handle scroll
const handleScroll = () => {
    isScrolled.value = window.scrollY > 50;
};

// Close menus on click outside
onClickOutside(userMenuRef, () => {
    showUserMenu.value = false;
});

onClickOutside(langMenuRef, () => {
    showLangMenu.value = false;
});

// Removed campusesMenuRef usage as it was inside v-for and caused array issues


// Logout function - sends POST request
const logout = async () => {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        const response = await fetch('/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        // Redirect to home page after logout
        window.location.href = '/';
    } catch (error) {
        console.error('Logout error:', error);
        // Still redirect even if there's an error
        window.location.href = '/';
    }
};

onMounted(() => {
    // Check theme to match backend logic
    const savedDarkMode = localStorage.getItem('darkMode');
    if (savedDarkMode === 'enabled' || (!savedDarkMode && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        isDarkMode.value = true;
    } else {
        isDarkMode.value = false;
    }
    updateTheme();

    window.addEventListener('scroll', handleScroll);
    fetchSettings();
    fetchCampuses();
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<style scoped>
.dropdown-enter-active,
.dropdown-leave-active {
    transition: all 0.2s ease;
}

.dropdown-enter-from,
.dropdown-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

.slide-down-enter-active,
.slide-down-leave-active {
    transition: all 0.3s ease;
}

.slide-down-enter-from,
.slide-down-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}
</style>
