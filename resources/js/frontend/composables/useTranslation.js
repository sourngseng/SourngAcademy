import { ref, readonly, computed } from 'vue';

// Translation data storage
const translations = ref({});
const currentLocale = ref(localStorage.getItem('frontend_locale') || 'en');
const isLoaded = ref(false);
const translationKey = ref(0); // Reactive key for forcing updates

// Load translations for a specific locale
const loadTranslations = async (locale) => {
    try {
        const response = await fetch(`/api/frontend/translations/${locale}`);
        if (response.ok) {
            const data = await response.json();
            if (data.success && data.data) {
                translations.value = data.data;
                isLoaded.value = true;
                translationKey.value++; // Increment to trigger reactivity
                return true;
            }
        }
    } catch (error) {
        console.error('Failed to load translations:', error);
    }
    
    // Fallback to empty object if load fails
    translations.value = {};
    isLoaded.value = true;
    return false;
};

// Translate function - reads reactive translations
const t = (key, fallback = null) => {
    // Access translationKey to make this reactive
    const _ = translationKey.value;
    if (translations.value[key]) {
        return translations.value[key];
    }
    return fallback || key;
};

// Set locale and reload translations
const setLocale = async (locale) => {
    currentLocale.value = locale;
    localStorage.setItem('frontend_locale', locale);
    await loadTranslations(locale);
};

// Initialize translations
const initTranslations = async () => {
    await loadTranslations(currentLocale.value);
    
    // Listen for locale change events from Navbar
    window.addEventListener('locale-changed', async (event) => {
        await setLocale(event.detail);
    });
};

// Composable export
export function useTranslation() {
    return {
        t,
        currentLocale: readonly(currentLocale),
        translations: readonly(translations),
        isLoaded: readonly(isLoaded),
        translationKey: readonly(translationKey),
        setLocale,
        initTranslations,
        loadTranslations
    };
}

// Export for direct use
export { t, currentLocale, translations, isLoaded, translationKey, setLocale, initTranslations, loadTranslations };
