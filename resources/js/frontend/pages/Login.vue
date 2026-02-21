<template>
    <div class="min-h-screen flex bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=2070&q=80'); background-size: cover; background-position: center;"></div>
        </div>
        
        <!-- Desktop Layout: Two Columns -->
        <div class="relative w-full flex flex-col lg:flex-row">
            <!-- Left Side - Branding (Hidden on Mobile) -->
            <div class="hidden lg:flex lg:w-1/2 items-center justify-center p-12">
                <div class="max-w-md text-center lg:text-left">
                    <!-- Logo -->
                    <router-link to="/" class="inline-flex items-center space-x-3 mb-8">
                        <img 
                            v-if="hotelLogo" 
                            :src="hotelLogo" 
                            :alt="hotelName"
                            class="h-16 w-auto object-contain"
                        >
                        <div v-else class="w-16 h-16 bg-gradient-to-r from-amber-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <span class="text-3xl font-bold text-white">{{ hotelName }}</span>
                    </router-link>
                    
                    <!-- Welcome Text -->
                    <h1 class="text-5xl font-bold text-white mb-4">{{ t('Welcome back') }}</h1>
                    <p class="text-xl text-gray-300 mb-8">{{ t('Sign in to your account to continue') }}</p>
                    
                    <!-- Features -->
                    <div class="space-y-4">
                        <div class="flex items-center text-gray-300">
                            <svg class="w-6 h-6 text-amber-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>{{ t('Manage your bookings easily') }}</span>
                        </div>
                        <div class="flex items-center text-gray-300">
                            <svg class="w-6 h-6 text-amber-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>{{ t('Exclusive member discounts') }}</span>
                        </div>
                        <div class="flex items-center text-gray-300">
                            <svg class="w-6 h-6 text-amber-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>{{ t('Fast and secure checkout') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
                <div class="w-full max-w-md space-y-8">
                    <!-- Mobile Logo (Visible only on mobile) -->
                    <div class="lg:hidden text-center">
                        <router-link to="/" class="inline-flex items-center space-x-2">
                            <img 
                                v-if="hotelLogo" 
                                :src="hotelLogo" 
                                :alt="hotelName"
                                class="h-12 w-auto object-contain"
                            >
                            <div v-else class="w-12 h-12 bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <span class="text-2xl font-bold text-white">{{ hotelName }}</span>
                        </router-link>
                        <h2 class="mt-6 text-3xl font-bold text-white">{{ t('Welcome back') }}</h2>
                        <p class="mt-2 text-gray-400">{{ t('Sign in to your account to continue') }}</p>
                    </div>
                    
                    <!-- Login Form -->
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 shadow-2xl border border-white/20">
                        <form @submit.prevent="handleLogin" class="space-y-6">
                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">{{ t('Email Address') }}</label>
                                <div class="relative">
                                    <input 
                                        v-model="form.email"
                                        type="email" 
                                        class="w-full px-4 py-3 pl-12 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                                        :placeholder="t('your@email.com')"
                                        required
                                    >
                                    <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Password -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">{{ t('Password') }}</label>
                                <div class="relative">
                                    <input 
                                        v-model="form.password"
                                        :type="showPassword ? 'text' : 'password'" 
                                        class="w-full px-4 py-3 pl-12 pr-12 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                                        placeholder="••••••••"
                                        required
                                    >
                                    <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    <button 
                                        type="button" 
                                        @click="showPassword = !showPassword"
                                        class="absolute right-4 top-3.5 text-gray-400 hover:text-white transition-colors"
                                    >
                                        <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Remember Me & Forgot Password -->
                            <div class="flex items-center justify-between">
                                <label class="flex items-center">
                                    <input 
                                        v-model="form.remember"
                                        type="checkbox" 
                                        class="w-4 h-4 text-amber-500 bg-white/5 border-white/10 rounded focus:ring-amber-500"
                                    >
                                    <span class="ml-2 text-sm text-gray-300">{{ t('Remember me') }}</span>
                                </label>
                                <a href="/password/reset" class="text-sm text-amber-400 hover:text-amber-300 transition-colors">
                                    {{ t('Forgot password?') }}
                                </a>
                            </div>
                            
                            <!-- Error Message -->
                            <div v-if="error" class="p-4 bg-red-500/20 border border-red-500/50 rounded-xl">
                                <p class="text-sm text-red-400">{{ error }}</p>
                            </div>
                            
                            <!-- Submit Button -->
                            <button 
                                type="submit"
                                :disabled="loading"
                                class="w-full py-4 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-semibold rounded-xl hover:from-amber-600 hover:to-amber-700 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                            >
                                <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ loading ? t('Signing in...') : t('Sign In') }}
                            </button>
                        </form>
                        
                        <!-- Divider -->
                        <div class="my-6 flex items-center">
                            <div class="flex-1 border-t border-white/10"></div>
                            <span class="px-4 text-sm text-gray-400">{{ t('or continue with') }}</span>
                            <div class="flex-1 border-t border-white/10"></div>
                        </div>
                        
                        <!-- Social Login -->
                        <div class="grid grid-cols-2 gap-4" v-if="googleEnabled || facebookEnabled || telegramEnabled">
                            <a v-if="googleEnabled" href="/auth/google" class="flex items-center justify-center px-4 py-3 bg-white/5 border border-white/10 rounded-xl hover:bg-white/10 transition-colors">
                                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                <span class="text-white text-sm font-medium">Google</span>
                            </a>
                            <a v-if="facebookEnabled" href="/auth/facebook" class="flex items-center justify-center px-4 py-3 bg-white/5 border border-white/10 rounded-xl hover:bg-white/10 transition-colors">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <span class="text-white text-sm font-medium">Facebook</span>
                            </a>
                            <div v-if="telegramEnabled" class="col-span-2 flex justify-center mt-2">
                                <div id="telegram-login-container"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Register Link -->
                    <p class="text-center text-gray-400">
                        {{ t("Don't have an account?") }} 
                        <router-link :to="registerUrl()" class="text-amber-400 hover:text-amber-300 font-semibold transition-colors">
                            {{ t('Create one') }}
                        </router-link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useTranslation } from '../composables/useTranslation';

const { t } = useTranslation();
const route = useRoute();
const router = useRouter();

// Hotel settings
const hotelName = ref('Luxury Hotel');
const hotelLogo = ref(null);

const form = ref({
    email: '',
    password: '',
    remember: false
});

const showPassword = ref(false);
const loading = ref(false);
const error = ref('');

// Get redirect URL from query params
const getRedirectUrl = () => {
    const redirect = route.query.redirect;
    if (redirect) {
        const decodedUrl = decodeURIComponent(redirect);
        if (decodedUrl.startsWith('/')) {
            return decodedUrl;
        }
    }
    return '/';
};

const handleLogin = async () => {
    loading.value = true;
    error.value = '';
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        const response = await fetch('/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(form.value)
        });
        
        if (response.ok) {
            const redirectUrl = getRedirectUrl();
            window.location.href = redirectUrl;
        } else {
            const data = await response.json();
            error.value = data.message || t('Invalid credentials. Please try again.');
        }
    } catch (e) {
        error.value = t('An error occurred. Please try again.');
    } finally {
        loading.value = false;
    }
};

// Build register URL with redirect param preserved
const registerUrl = () => {
    const redirect = route.query.redirect;
    if (redirect) {
        return `/register?redirect=${encodeURIComponent(redirect)}`;
    }
    return '/register';
};

const googleEnabled = ref(false);
const facebookEnabled = ref(false);
const telegramEnabled = ref(false);
const telegramBotName = ref('');

// Load Telegram Widget Script
const loadTelegramWidget = () => {
    if (!telegramBotName.value) return;

    const script = document.createElement('script');
    script.async = true;
    script.src = 'https://telegram.org/js/telegram-widget.js?22';
    script.setAttribute('data-telegram-login', telegramBotName.value);
    script.setAttribute('data-size', 'large');
    script.setAttribute('data-radius', '10');
    script.setAttribute('data-auth-url', window.location.origin + '/auth/telegram/callback');
    script.setAttribute('data-request-access', 'write');
    
    const container = document.getElementById('telegram-login-container');
    if (container) {
        container.innerHTML = ''; // Clear previous if any
        container.appendChild(script);
    }
};

// Fetch hotel settings from API
const fetchSettings = async () => {
    try {
        const response = await fetch('/api/frontend/settings');
        const data = await response.json();
        if (data.success && data.data) {
            hotelName.value = data.data.hotel_name || 'Luxury Hotel';
            hotelLogo.value = data.data.logo || null;
            
            // Social Login Settings
            googleEnabled.value = data.data.google_login_enabled;
            facebookEnabled.value = data.data.facebook_login_enabled;
            telegramEnabled.value = data.data.telegram_login_enabled;
            // Sanitize bot username (remove @ if present)
            telegramBotName.value = data.data.telegram_bot_username ? data.data.telegram_bot_username.replace('@', '').trim() : '';

            // Load Telegram widget if enabled
            if (telegramEnabled.value && telegramBotName.value) {
                // Determine if we need to delay slightly to ensure DOM is ready
                setTimeout(loadTelegramWidget, 500);
            }
        }
    } catch (error) {
        console.log('Using default hotel settings');
    }
};

onMounted(() => {
    fetchSettings();
});
</script>
