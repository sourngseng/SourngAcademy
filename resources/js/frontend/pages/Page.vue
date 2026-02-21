<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Page Header -->
        <section class="relative py-20 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: url('https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=2070&q=80'); background-size: cover; background-position: center;"></div>
            </div>
            
            <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div v-if="loading" class="animate-pulse">
                    <div class="h-12 bg-white/10 rounded-lg w-1/2 mx-auto mb-4"></div>
                </div>
                <template v-else>
                    <div v-if="page?.icon" class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-2xl">
                        <i :class="page.icon" class="text-3xl text-white"></i>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                        {{ page?.title || 'Page' }}
                    </h1>
                </template>
            </div>
        </section>

        <!-- Page Content -->
        <section class="py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Loading State -->
                <div v-if="loading" class="space-y-4 animate-pulse">
                    <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                    <div class="h-4 bg-gray-200 rounded w-full"></div>
                    <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                    <div class="h-4 bg-gray-200 rounded w-full"></div>
                    <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                </div>

                <!-- Error State -->
                <div v-else-if="error" class="text-center py-12">
                    <div class="w-20 h-20 mx-auto mb-6 bg-red-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-3xl text-red-500"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ t('Page Not Found') }}</h2>
                    <p class="text-gray-600 mb-6">{{ t("The page you're looking for doesn't exist or has been moved.") }}</p>
                    <router-link to="/" class="inline-flex items-center px-6 py-3 bg-amber-500 text-white font-semibold rounded-xl hover:bg-amber-600 transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        {{ t('Back to Home') }}
                    </router-link>
                </div>

                <!-- Content -->
                <div v-else class="bg-white rounded-2xl shadow-lg p-8 md:p-12">
                    <div class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-p:text-gray-600 prose-a:text-amber-600 prose-a:no-underline hover:prose-a:underline prose-strong:text-gray-900 prose-ul:text-gray-600 prose-ol:text-gray-600" v-html="page?.content"></div>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { t } from '../composables/useTranslation';

const route = useRoute();
const page = ref(null);
const loading = ref(true);
const error = ref(false);

const fetchPage = async (slug) => {
    loading.value = true;
    error.value = false;
    
    try {
        const response = await fetch(`/api/frontend/pages/${slug}`);
        const data = await response.json();
        
        if (data.success) {
            page.value = data.data;
            
            // Update document title
            if (page.value.meta_title) {
                document.title = page.value.meta_title;
            }
        } else {
            error.value = true;
        }
    } catch (e) {
        console.error('Failed to fetch page:', e);
        error.value = true;
    } finally {
        loading.value = false;
    }
};

// Watch for route changes
watch(() => route.params.slug, (newSlug) => {
    if (newSlug) {
        fetchPage(newSlug);
    }
});

onMounted(() => {
    if (route.params.slug) {
        fetchPage(route.params.slug);
    }
});
</script>

<style scoped>
/* Custom prose styling for page content */
:deep(.prose h2) {
    @apply text-2xl font-bold mt-8 mb-4;
}

:deep(.prose h3) {
    @apply text-xl font-semibold mt-6 mb-3;
}

:deep(.prose p) {
    @apply mb-4 leading-relaxed;
}

:deep(.prose ul) {
    @apply list-disc pl-6 mb-4 space-y-2;
}

:deep(.prose ol) {
    @apply list-decimal pl-6 mb-4 space-y-2;
}

:deep(.prose li) {
    @apply text-gray-600;
}

:deep(.prose strong) {
    @apply font-semibold;
}

:deep(.prose a) {
    @apply text-amber-600 hover:text-amber-700;
}
</style>
