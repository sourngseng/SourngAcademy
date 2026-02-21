<template>
    <div class="min-h-screen flex bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=2070&q=80'); background-size: cover; background-position: center;"></div>
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
                    <h1 class="text-5xl font-bold text-white mb-4">{{ t('Join Us Today') }}</h1>
                    <p class="text-xl text-gray-300 mb-8">{{ t('Create an account for exclusive member benefits') }}</p>
                    
                    <!-- Features -->
                    <div class="space-y-4">
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
                            <span>{{ t('Early access to promotions') }}</span>
                        </div>
                        <div class="flex items-center text-gray-300">
                            <svg class="w-6 h-6 text-amber-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>{{ t('Manage bookings easily') }}</span>
                        </div>
                        <div class="flex items-center text-gray-300">
                            <svg class="w-6 h-6 text-amber-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>{{ t('Earn loyalty points') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
                <div class="w-full max-w-md space-y-6">
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
                        <h2 class="mt-6 text-3xl font-bold text-white">{{ t('Create your account') }}</h2>
                        <p class="mt-2 text-gray-400">{{ t('Join us for exclusive member benefits') }}</p>
                    </div>
                    
                    <!-- Register Form -->
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 shadow-2xl border border-white/20">
                        <form @submit.prevent="handleRegister" class="space-y-5">
                            <!-- Name -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">{{ t('First Name') }}</label>
                                    <input 
                                        v-model="form.firstName"
                                        type="text" 
                                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                                        :placeholder="t('John')"
                                        required
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">{{ t('Last Name') }}</label>
                                    <input 
                                        v-model="form.lastName"
                                        type="text" 
                                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                                        :placeholder="t('Doe')"
                                        required
                                    >
                                </div>
                            </div>
                            
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
                            
                            <!-- Phone -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">{{ t('Phone Number') }}</label>
                                <div class="relative">
                                    <input 
                                        v-model="form.phone"
                                        type="tel" 
                                        class="w-full px-4 py-3 pl-12 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                                        placeholder="+855 12 345 678"
                                    >
                                    <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
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
                                        minlength="8"
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
                                <!-- Password Strength -->
                                <div class="mt-2">
                                    <div class="flex gap-1">
                                        <div class="h-1 flex-1 rounded" :class="passwordStrength >= 1 ? 'bg-red-500' : 'bg-white/10'"></div>
                                        <div class="h-1 flex-1 rounded" :class="passwordStrength >= 2 ? 'bg-yellow-500' : 'bg-white/10'"></div>
                                        <div class="h-1 flex-1 rounded" :class="passwordStrength >= 3 ? 'bg-green-400' : 'bg-white/10'"></div>
                                        <div class="h-1 flex-1 rounded" :class="passwordStrength >= 4 ? 'bg-green-500' : 'bg-white/10'"></div>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-400">{{ passwordStrengthText }}</p>
                                </div>
                            </div>
                            
                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">{{ t('Confirm Password') }}</label>
                                <input 
                                    v-model="form.passwordConfirmation"
                                    type="password" 
                                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                                    placeholder="••••••••"
                                    required
                                >
                                <p v-if="form.passwordConfirmation && form.password !== form.passwordConfirmation" class="mt-1 text-xs text-red-400">
                                    {{ t('Passwords do not match') }}
                                </p>
                            </div>
                            
                            <!-- Terms -->
                            <div class="flex items-start">
                                <input 
                                    v-model="form.terms"
                                    type="checkbox" 
                                    class="w-4 h-4 mt-1 text-amber-500 bg-white/5 border-white/10 rounded focus:ring-amber-500"
                                    required
                                >
                                <span class="ml-2 text-sm text-gray-300">
                                    {{ t('I agree to the') }} 
                                    <a href="#" class="text-amber-400 hover:text-amber-300">{{ t('Terms of Service') }}</a>
                                    {{ t('and') }} 
                                    <a href="#" class="text-amber-400 hover:text-amber-300">{{ t('Privacy Policy') }}</a>
                                </span>
                            </div>
                            
                            <!-- Error Message -->
                            <div v-if="error" class="p-4 bg-red-500/20 border border-red-500/50 rounded-xl">
                                <p class="text-sm text-red-400">{{ error }}</p>
                            </div>
                            
                            <!-- Submit Button -->
                            <button 
                                type="submit"
                                :disabled="loading || !isFormValid"
                                class="w-full py-4 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-semibold rounded-xl hover:from-amber-600 hover:to-amber-700 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                            >
                                <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ loading ? t('Creating account...') : t('Create Account') }}
                            </button>
                        </form>
                    </div>
                    
                    <!-- Login Link -->
                    <p class="text-center text-gray-400">
                        {{ t('Already have an account?') }} 
                        <router-link :to="loginUrl" class="text-amber-400 hover:text-amber-300 font-semibold transition-colors">
                            {{ t('Sign in') }}
                        </router-link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useTranslation } from '../composables/useTranslation';

const { t } = useTranslation();
const route = useRoute();
const router = useRouter();

// Hotel settings
const hotelName = ref('Luxury Hotel');
const hotelLogo = ref(null);

const form = ref({
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    password: '',
    passwordConfirmation: '',
    terms: false
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

// Build login URL with redirect param preserved
const loginUrl = computed(() => {
    const redirect = route.query.redirect;
    if (redirect) {
        return `/login?redirect=${encodeURIComponent(redirect)}`;
    }
    return '/login';
});

const passwordStrength = computed(() => {
    const password = form.value.password;
    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    return strength;
});

const passwordStrengthText = computed(() => {
    const strength = passwordStrength.value;
    if (!form.value.password) return t('Enter a password');
    if (strength <= 1) return t('Weak - add uppercase, numbers, symbols');
    if (strength === 2) return t('Fair - add more variety');
    if (strength === 3) return t('Good - almost there');
    return t('Strong password');
});

const isFormValid = computed(() => {
    return form.value.firstName &&
           form.value.lastName &&
           form.value.email &&
           form.value.password.length >= 8 &&
           form.value.password === form.value.passwordConfirmation &&
           form.value.terms;
});

const handleRegister = async () => {
    if (!isFormValid.value) return;
    
    loading.value = true;
    error.value = '';
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        const response = await fetch('/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: `${form.value.firstName} ${form.value.lastName}`,
                email: form.value.email,
                phone: form.value.phone,
                password: form.value.password,
                password_confirmation: form.value.passwordConfirmation
            })
        });
        
        if (response.ok) {
            const redirectUrl = getRedirectUrl();
            window.location.href = redirectUrl;
        } else {
            const data = await response.json();
            error.value = data.message || Object.values(data.errors || {})[0]?.[0] || t('Registration failed.');
        }
    } catch (e) {
        error.value = t('An error occurred. Please try again.');
    } finally {
        loading.value = false;
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
        }
    } catch (error) {
        console.log('Using default hotel settings');
    }
};

onMounted(() => {
    fetchSettings();
});
</script>
