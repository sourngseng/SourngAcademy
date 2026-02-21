<template>
    <div v-if="loading" class="min-h-screen bg-gray-50 pt-24 pb-12 flex justify-center items-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-amber-500"></div>
    </div>
    
    <div v-else-if="post" class="bg-white">
        <!-- SEO Head -->
        <Head>
            <title>{{ post.title }} - Sourng Academy</title>
            <meta name="description" :content="post.excerpt">
            <meta property="og:title" :content="post.title">
            <meta property="og:description" :content="post.excerpt">
            <meta property="og:image" :content="post.image">
            <meta property="twitter:card" content="summary_large_image">
        </Head>

        <!-- Article Header -->
        <header class="relative pt-32 pb-12 bg-gray-900">
            <div class="absolute inset-0 bg-cover bg-center opacity-40" :style="`background-image: url('${post.image}')`"></div>
            <div class="absolute inset-x-0 bottom-0 top-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent"></div>
            
            <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div v-if="post.category" class="mb-4">
                    <span class="px-3 py-1 bg-amber-500 text-white text-sm font-semibold rounded-full uppercase tracking-wider">
                        {{ post.category }}
                    </span>
                </div>
                <h1 class="text-3xl md:text-5xl font-bold text-white mb-6 leading-tight">
                    {{ post.title }}
                </h1>
                <div class="flex items-center justify-center text-gray-300 text-sm md:text-base space-x-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ post.date }}
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ post.readTime }} min read
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        {{ post.author }}
                    </div>
                </div>
            </div>
        </header>

<!-- Article Content & Sidebar -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Main Content -->
                <article class="lg:col-span-2">
                    <!-- Top Ad Banner -->
                    <div v-if="settings.blog_ad_top_type === 'code'" v-html="settings.blog_ad_top_code" class="mb-8 flex justify-center overflow-hidden"></div>
                    <div v-else-if="settings.blog_ad_top_type === 'image' && settings.blog_ad_top_image" class="mb-8">
                         <a :href="settings.blog_ad_top_url" target="_blank" rel="noopener noreferrer" class="block w-full">
                            <img :src="settings.blog_ad_top_image" alt="Advertisement" class="w-full h-auto rounded-xl shadow-sm object-cover max-h-60 mx-auto">
                         </a>
                    </div>

                    <!-- Content -->
                    <div class="prose prose-lg prose-indigo max-w-none text-gray-600" v-html="post.content"></div>
                    
                    <!-- Bottom Ad Banner -->
                    <div v-if="settings.blog_ad_bottom_type === 'code'" v-html="settings.blog_ad_bottom_code" class="my-8 flex justify-center overflow-hidden"></div>
                    <div v-else-if="settings.blog_ad_bottom_type === 'image' && settings.blog_ad_bottom_image" class="my-8">
                         <a :href="settings.blog_ad_bottom_url" target="_blank" rel="noopener noreferrer" class="block w-full">
                            <img :src="settings.blog_ad_bottom_image" alt="Advertisement" class="w-full h-auto rounded-xl shadow-sm object-cover max-h-60 mx-auto">
                         </a>
                    </div>

                    <!-- Tags & Share -->
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-center">
                            <div class="mb-4 md:mb-0">
                                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-2">Share this article</h3>
                                <div class="flex space-x-4">
                                    <a :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(postUrl)}`" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-blue-600 transition-colors">
                                        <span class="sr-only">Facebook</span>
                                        <i class="fab fa-facebook text-xl"></i>
                                    </a>
                                    <a :href="`https://twitter.com/intent/tweet?text=${encodeURIComponent(post.title)}&url=${encodeURIComponent(postUrl)}`" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-sky-500 transition-colors">
                                        <span class="sr-only">Twitter</span>
                                        <i class="fab fa-twitter text-xl"></i>
                                    </a>
                                    <a :href="`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(postUrl)}`" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-blue-700 transition-colors">
                                        <span class="sr-only">LinkedIn</span>
                                        <i class="fab fa-linkedin text-xl"></i>
                                    </a>
                                    <a :href="`https://t.me/share/url?url=${encodeURIComponent(postUrl)}&text=${encodeURIComponent(post.title)}`" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-blue-500 transition-colors">
                                        <span class="sr-only">Telegram</span>
                                        <i class="fab fa-telegram text-xl"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Sidebar -->
                <aside class="lg:col-span-1 space-y-8 mt-12 lg:mt-0">
                    <!-- Top Sidebar Ads -->
                    <div v-if="settings.blog_sidebar_ad_top_type === 'code'" class="bg-gray-50 rounded-xl p-4 border border-gray-100 mb-8 overflow-hidden flex justify-center">
                         <div v-html="settings.blog_sidebar_ad_top_code"></div>
                    </div>
                    <div v-else-if="settings.blog_sidebar_ad_top_type === 'image' && settings.blog_sidebar_ad_top_image" class="mb-8">
                         <h3 class="text-lg font-bold text-gray-900 mb-4 px-2 border-l-4 border-amber-500">Sponsored</h3>
                         <a :href="settings.blog_sidebar_ad_top_url" target="_blank" rel="noopener noreferrer" class="block w-full">
                            <img :src="settings.blog_sidebar_ad_top_image" alt="Advertisement" class="w-full h-auto rounded-xl shadow-sm border border-gray-100">
                         </a>
                    </div>

                    <!-- Categories Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-5 border-b border-gray-50 bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-900 mb-0 px-2 border-l-4 border-amber-500">Categories</h3>
                        </div>
                        <ul class="divide-y divide-gray-100" v-if="categories.length > 0">
                            <li v-for="category in categories" :key="category.id">
                                <router-link :to="`/blog?category=${category.slug}`" class="block p-4 hover:bg-gray-50 transition-colors flex justify-between items-center group">
                                    <span class="text-gray-700 font-medium group-hover:text-amber-600 transition-colors">{{ category.name }}</span>
                                    <span class="bg-gray-100 text-gray-500 py-1 px-3 rounded-full text-xs font-semibold group-hover:bg-amber-100 group-hover:text-amber-600 transition-colors">{{ category.posts_count }}</span>
                                </router-link>
                            </li>
                        </ul>
                        <div v-else class="p-8 text-center text-gray-400">
                             <p>No categories found</p>
                        </div>
                    </div>

                    <!-- Bottom Sidebar Ads -->
                    <div v-if="settings.blog_sidebar_ad_bottom_type === 'code'" class="bg-gray-50 rounded-xl p-4 border border-gray-100 sticky top-24 overflow-hidden flex justify-center">
                         <div v-html="settings.blog_sidebar_ad_bottom_code"></div>
                    </div>
                    <div v-else-if="settings.blog_sidebar_ad_bottom_type === 'image' && settings.blog_sidebar_ad_bottom_image" class="sticky top-24">
                         <h3 class="text-lg font-bold text-gray-900 mb-4 px-2 border-l-4 border-amber-500">Sponsored</h3>
                         <a :href="settings.blog_sidebar_ad_bottom_url" target="_blank" rel="noopener noreferrer" class="block w-full">
                            <img :src="settings.blog_sidebar_ad_bottom_image" alt="Advertisement" class="w-full h-auto rounded-xl shadow-sm border border-gray-100">
                         </a>
                    </div>
                </aside>
            </div>
        </div>
        
        <!-- Related Posts (Optional) -->
    </div>
    
    <div v-else class="min-h-screen flex flex-col items-center justify-center bg-gray-50 px-4">
        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h2 class="text-xl font-bold text-gray-900 mb-2">Post not found</h2>
        <p class="text-gray-500 mb-6">The article you are looking for does not exist or has been removed.</p>
        <router-link to="/blog" class="px-6 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors">
            Back to Blog
        </router-link>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import { useRoute } from 'vue-router';
// Note: In a real Vue 3 app with meta management, you'd use useHead from @vueuse/head or similiar
// Here we just update document title manually in onMounted for simplicity if Head component isn't available globally

const route = useRoute();
const post = ref(null);
const categories = ref([]);
const settings = ref({});
const loading = ref(true);

const postUrl = computed(() => window.location.href);

const fetchSettings = async () => {
    try {
        const response = await fetch('/api/frontend/settings');
        const data = await response.json();
        if (data.success) {
            settings.value = data.data;
        }
    } catch (error) {
        console.error('Error fetching settings:', error);
    }
};

const fetchPost = async (slug) => {
    loading.value = true;
    try {
        const response = await fetch(`/api/frontend/blog-posts/${slug}`);
        const data = await response.json();
        
        if (data.success) {
            post.value = {
                id: data.data.id,
                title: data.data.title,
                excerpt: data.data.excerpt,
                content: data.data.content,
                image: data.data.image,
                category: data.data.category?.name,
                date: data.data.published_at,
                readTime: data.data.read_time,
                author: data.data.author
            };
            
            // Basic SEO update
            document.title = `${post.value.title} - Sourng Academy`;
            updateMetaTags(post.value);
        } else {
            post.value = null;
        }
    } catch (error) {
        console.error('Error fetching post:', error);
        post.value = null;
    } finally {
        loading.value = false;
    }
};

const fetchCategories = async () => {
    try {
        const response = await fetch('/api/frontend/blog-categories');
        const data = await response.json();
        if (data.success) {
            categories.value = data.data;
        }
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
};

const updateMetaTags = (postData) => {
    // Helper to update meta tags dynamically
    const setMeta = (property, content) => {
        let element = document.querySelector(`meta[property="${property}"]`);
        if (!element) {
            element = document.createElement('meta');
            element.setAttribute('property', property);
            document.head.appendChild(element);
        }
        element.setAttribute('content', content);
    };

    const setNameMeta = (name, content) => {
        let element = document.querySelector(`meta[name="${name}"]`);
        if (!element) {
            element = document.createElement('meta');
            element.setAttribute('name', name);
            document.head.appendChild(element);
        }
        element.setAttribute('content', content);
    };
    
    // Title
    document.title = `${postData.title} - Sourng Academy`;
    setMeta('og:title', postData.title);
    setNameMeta('twitter:title', postData.title);

    // Description
    const description = postData.excerpt || postData.content.substring(0, 160).replace(/<[^>]*>?/gm, '');
    setNameMeta('description', description);
    setMeta('og:description', description);
    setNameMeta('twitter:description', description);

    // Image
    if (postData.image) {
        setMeta('og:image', postData.image);
        setNameMeta('twitter:image', postData.image);
        setNameMeta('twitter:card', 'summary_large_image');
    }

    // URL
    setMeta('og:url', window.location.href);
    
    // Type
    setMeta('og:type', 'article');
    
    // Site Name
    setMeta('og:site_name', 'Sourng Academy');
};

onMounted(() => {
    fetchPost(route.params.slug);
    fetchCategories();
    fetchSettings();
});

watch(() => route.params.slug, (newSlug) => {
    if (newSlug) fetchPost(newSlug);
});
</script>

<style scoped>
/* Add typography styles if @tailwindcss/typography is not installed */
.prose h2 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-top: 2em;
    margin-bottom: 1em;
    color: #111827;
}
.prose p {
    margin-bottom: 1.25em;
    line-height: 1.75;
}
.prose ul {
    list-style-type: disc;
    padding-left: 1.25em;
    margin-bottom: 1.25em;
}
.prose img {
    border-radius: 0.5rem;
    margin-top: 2em;
    margin-bottom: 2em;
}
</style>
