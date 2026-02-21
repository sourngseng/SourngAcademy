<template>
    <div class="min-h-screen flex flex-col" :style="fontStyle" :class="{ 'khmer-locale': currentLocale === 'km' }">
        <Navbar />
        <main class="flex-grow">
            <router-view v-slot="{ Component }">
                <transition name="fade" mode="out-in">
                    <component :is="Component" />
                </transition>
            </router-view>
        </main>
        <Footer />
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import Navbar from './components/Navbar.vue';
import Footer from './components/Footer.vue';
import { useTranslation } from './composables/useTranslation';

const { currentLocale } = useTranslation();

// Font settings from API
const primaryFont = ref('Inter');
const khmerFont = ref('Kantumruy Pro');

// Font mapping for Khmer fonts
const khmerFontMap = {
    'kantumruy': "'Kantumruy Pro', sans-serif",
    'hanuman': "'Hanuman', serif",
};

// Get the actual Khmer font family string
const getKhmerFontFamily = () => {
    return khmerFontMap[khmerFont.value] || khmerFontMap['kantumruy'];
};

// Compute the font style based on current locale
const fontStyle = computed(() => {
    const locale = currentLocale.value;
    
    if (locale === 'km') {
        const fontFamily = getKhmerFontFamily();
        return { 
            fontFamily,
            '--khmer-font': fontFamily
        };
    }
    
    return { 
        fontFamily: `'${primaryFont.value}', sans-serif`,
        '--khmer-font': getKhmerFontFamily()
    };
});

// Fetch settings on mount
onMounted(async () => {
    try {
        const response = await fetch('/api/frontend/settings');
        const result = await response.json();
        
        if (result.success && result.data) {
            if (result.data.primary_font) {
                primaryFont.value = result.data.primary_font;
            }
            if (result.data.khmer_font) {
                khmerFont.value = result.data.khmer_font;
            }
        }
    } catch (error) {
        console.error('Failed to load font settings:', error);
    }
});
</script>

<style>
/* Import Google Fonts - All available font options */
@import url('https://fonts.googleapis.com/css2?family=Hanuman:wght@400;700&family=Inter:wght@300;400;500;600;700;800&family=Kantumruy+Pro:wght@300;400;500;600;700&family=Lato:wght@300;400;700&family=Montserrat:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap');

/* Apply Khmer font to all headings when in Khmer locale */
.khmer-locale h1,
.khmer-locale h2,
.khmer-locale h3,
.khmer-locale h4,
.khmer-locale .heading-khmer {
    font-family: var(--khmer-font, 'Kantumruy Pro', sans-serif) !important;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* PrimeVue DatePicker Month/Year Title Fix */
.p-datepicker-title .p-datepicker-select-month,
.p-datepicker-title .p-datepicker-select-year {
    color: #111827 !important;
    font-weight: 600 !important;
}

.p-datepicker-title .p-datepicker-select-month:hover,
.p-datepicker-title .p-datepicker-select-year:hover {
    color: #f59e0b !important;
}

/* DatePicker Header Navigation */
.p-datepicker-header {
    background: white !important;
    border-bottom: 1px solid #e5e7eb !important;
}

.p-datepicker-prev-button,
.p-datepicker-next-button {
    color: #374151 !important;
}

.p-datepicker-prev-button:hover,
.p-datepicker-next-button:hover {
    color: #f59e0b !important;
    background: #fef3c7 !important;
}

/* DatePicker Day Cells */
.p-datepicker-day-cell.p-datepicker-today .p-datepicker-day {
    background: #fef3c7 !important;
    color: #92400e !important;
}

.p-datepicker-day-cell .p-datepicker-day.p-datepicker-day-selected {
    background: #f59e0b !important;
    color: white !important;
}

.p-datepicker-day-cell .p-datepicker-day:hover:not(.p-datepicker-day-selected) {
    background: #fef3c7 !important;
    color: #92400e !important;
}
</style>
