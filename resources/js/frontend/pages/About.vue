<template>
    <div>
        <!-- Page Header -->
        <section class="relative pt-32 pb-20 bg-gray-900">
            <div class="absolute inset-0 bg-cover bg-center opacity-30" style="background-image: url('https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=2070&q=80')"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl sm:text-5xl font-bold text-white mb-4">{{ t('About Us') }}</h1>
                <p class="text-gray-300 text-lg max-w-2xl mx-auto">
                    {{ t('About Page Subtitle') }}
                </p>
            </div>
        </section>

        <!-- Story Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <span class="text-amber-500 font-semibold text-sm uppercase tracking-wider">{{ t('Our Story') }}</span>
                        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mt-2 mb-6">{{ hotelSettings.about_title }}</h2>
                        <div class="text-gray-600 leading-relaxed mb-6 space-y-4" v-if="hotelSettings.about_text" v-html="hotelSettings.about_text"></div>
                        
                        <div class="flex items-center space-x-8" v-if="hotelSettings.years_experience || hotelSettings.total_rooms || hotelSettings.total_guests">
                            <div v-if="hotelSettings.years_experience">
                                <div class="text-3xl font-bold text-amber-500">{{ hotelSettings.years_experience }}+</div>
                                <div class="text-gray-600">{{ t('Years of Excellence') }}</div>
                            </div>
                            <div v-if="hotelSettings.total_rooms">
                                <div class="text-3xl font-bold text-amber-500">{{ hotelSettings.total_rooms }}+</div>
                                <div class="text-gray-600">{{ t('Rooms') }}</div>
                            </div>
                            <div v-if="hotelSettings.total_guests">
                                <div class="text-3xl font-bold text-amber-500">{{ hotelSettings.total_guests }}</div>
                                <div class="text-gray-600">{{ t('Happy Students') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="relative" v-if="hotelSettings.about_image">
                        <img 
                            :src="hotelSettings.about_image" 
                            alt="University Campus"
                            class="rounded-2xl shadow-2xl"
                        >
                        <div class="absolute -bottom-8 -left-8 bg-amber-500 text-white p-6 rounded-2xl shadow-xl hidden lg:block" v-if="hotelSettings.rating">
                            <div class="text-4xl font-bold">{{ hotelSettings.rating }}</div>
                            <div class="flex items-center mt-1">
                                <template v-for="i in 5" :key="i">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </template>
                            </div>
                            <div class="text-sm mt-1">{{ t('Student Satisfaction') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Values Section -->
        <section class="py-20 bg-gray-50" v-if="hotelSettings.about_values && hotelSettings.about_values.length > 0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <span class="text-amber-500 font-semibold text-sm uppercase tracking-wider">{{ t('Our Values') }}</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mt-2">{{ t('What We Stand For') }}</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div v-for="(val, index) in hotelSettings.about_values" :key="index" class="bg-white rounded-2xl p-8 shadow-lg text-center">
                        <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="val.icon || valueIcons[index % valueIcons.length]"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ val.title }}</h3>
                        <p class="text-gray-600">{{ val.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team Section -->
        <section class="py-20 bg-white" v-if="team.length > 0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <span class="text-amber-500 font-semibold text-sm uppercase tracking-wider">{{ t('Our Team') }}</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mt-2">{{ t('Meet the People Behind the Magic') }}</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div v-for="member in team" :key="member.name" class="text-center group">
                        <div class="relative mb-6 rounded-2xl overflow-hidden">
                            <img 
                                :src="member.image" 
                                :alt="member.name"
                                class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-500"
                            >
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">{{ member.name }}</h3>
                        <p class="text-amber-500 font-medium">{{ member.position }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { t, currentLocale } from '../composables/useTranslation';

const valueIcons = [
    'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
    'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z',
    'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
];

const hotelSettings = ref({
    hotel_name: '',
    founded_year: '',
    city: '',
    years_experience: '',
    total_rooms: '',
    total_guests: '',
    rating: '',
    about_text: '',
    about_image: '',
    about_title: '',
    about_values: []
});

const team = ref([]);

// Fetch hotel settings from API
const fetchSettings = async () => {
    try {
        const response = await fetch(`/api/frontend/settings?locale=${currentLocale.value}`);
        const data = await response.json();
        if (data.success && data.data) {
            hotelSettings.value = {
                hotel_name: data.data.hotel_name,
                founded_year: data.data.founded_year,
                city: data.data.city,
                years_experience: data.data.years_experience,
                total_rooms: data.data.total_rooms,
                total_guests: data.data.total_guests,
                rating: data.data.rating,
                about_title: data.data.about_title,
                about_text: data.data.about_text,
                about_image: data.data.about_image,
                about_values: data.data.about_values
            };
            
            // Update team if provided in settings
            if (data.data.team && data.data.team.length > 0) {
                team.value = data.data.team;
            }
        }
    } catch (error) {
        console.log('Error fetching settings:', error);
    }
};

onMounted(() => {
    fetchSettings();
});

// Watch for locale changes and re-fetch settings
watch(currentLocale, () => {
    fetchSettings();
});
</script>
