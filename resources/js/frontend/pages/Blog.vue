<template>
    <div>
        <!-- Page Header -->
        <section class="relative pt-32 pb-20 bg-gray-900">
            <div class="absolute inset-0 bg-cover bg-center opacity-30" style="background-image: url('https://images.unsplash.com/photo-1455587734955-081b22074882?auto=format&fit=crop&w=2070&q=80')"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl sm:text-5xl font-bold text-white mb-4">Blog & News</h1>
                <p class="text-gray-300 text-lg max-w-2xl mx-auto">
                    Stay updated with the latest news, travel tips, and stories from our hotel.
                </p>
            </div>
        </section>

        <!-- Category Filter -->
        <section class="py-8 bg-white border-b sticky top-20 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-gray-600 font-medium">Filter by:</span>
                    <button 
                        @click="selectedCategory = ''"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-all"
                        :class="selectedCategory === '' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                    >
                        All Posts
                    </button>
                    <button 
                        v-for="category in categories" 
                        :key="category.id"
                        @click="selectedCategory = category.id"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-all"
                        :class="selectedCategory === category.id ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                    >
                        {{ category.name }}
                    </button>
                </div>
            </div>
        </section>

        <!-- Blog Posts -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Loading State -->
                <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="i in 6" :key="i" class="bg-white rounded-2xl overflow-hidden shadow-lg animate-pulse">
                        <div class="h-56 bg-gray-200"></div>
                        <div class="p-6">
                            <div class="h-4 bg-gray-200 rounded w-1/4 mb-3"></div>
                            <div class="h-6 bg-gray-200 rounded w-3/4 mb-3"></div>
                            <div class="h-4 bg-gray-200 rounded w-full mb-2"></div>
                            <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                        </div>
                    </div>
                </div>

                <!-- No Posts -->
                <div v-else-if="filteredPosts.length === 0" class="text-center py-16">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No posts found</h3>
                    <p class="text-gray-500">No blog posts available in this category.</p>
                </div>

                <!-- Posts Grid -->
                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <article 
                        v-for="post in filteredPosts" 
                        :key="post.id"
                        class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 group"
                    >
                        <div class="relative h-56 overflow-hidden">
                            <router-link :to="{ name: 'blog-post', params: { slug: post.slug } }">
                                <img 
                                    :src="post.image" 
                                    :alt="post.title"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                >
                            </router-link>
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 bg-amber-500 text-white text-sm font-semibold rounded-full">
                                    {{ post.category }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                <span>{{ post.date }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ post.readTime }} min read</span>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-amber-500 transition-colors">
                                {{ post.title }}
                            </h2>
                            <p class="text-gray-600 mb-4 line-clamp-1 h-5 text-sm">{{ post.excerpt }}</p>
                            <router-link :to="{ name: 'blog-post', params: { slug: post.slug } }" class="inline-flex items-center text-amber-500 font-semibold hover:text-amber-600 transition-colors">
                                Read More
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </router-link>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const posts = ref([]);
const categories = ref([]);
const selectedCategory = ref('');
const loading = ref(true);

// Filter posts by category
const filteredPosts = computed(() => {
    if (!selectedCategory.value) {
        return posts.value;
    }
    return posts.value.filter(post => post.categoryId === selectedCategory.value);
});

// Default posts for fallback
const defaultPosts = [
    {
        id: 1,
        title: '10 Must-Visit Attractions Near Our Hotel',
        excerpt: 'Discover the best local attractions and hidden gems within walking distance of our hotel.',
        category: 'Travel Tips',
        categoryId: null,
        date: 'Jan 15, 2026',
        readTime: 5,
        image: 'https://images.unsplash.com/photo-1569949381669-ecf31ae8e613?auto=format&fit=crop&w=800&q=80'
    },
    {
        id: 2,
        title: 'A Guide to Cambodian Cuisine',
        excerpt: 'Explore the rich flavors and unique dishes that make Cambodian food unforgettable.',
        category: 'Food & Dining',
        categoryId: null,
        date: 'Jan 10, 2026',
        readTime: 7,
        image: 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=800&q=80'
    },
    {
        id: 3,
        title: 'Wellness Retreat: Our New Spa Services',
        excerpt: 'Introducing our expanded spa menu featuring traditional Khmer healing treatments.',
        category: 'Wellness',
        categoryId: null,
        date: 'Jan 5, 2026',
        readTime: 4,
        image: 'https://images.unsplash.com/photo-1540555700478-4be289fbec6c?auto=format&fit=crop&w=800&q=80'
    },
    {
        id: 4,
        title: 'Planning the Perfect Wedding at Our Hotel',
        excerpt: 'Everything you need to know about hosting your dream wedding at our venue.',
        category: 'Events',
        categoryId: null,
        date: 'Dec 28, 2025',
        readTime: 6,
        image: 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80'
    },
    {
        id: 5,
        title: 'Sustainable Tourism: Our Green Initiatives',
        excerpt: 'Learn about our commitment to sustainability and eco-friendly practices.',
        category: 'Sustainability',
        categoryId: null,
        date: 'Dec 20, 2025',
        readTime: 5,
        image: 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80'
    },
    {
        id: 6,
        title: 'Best Season to Visit Cambodia',
        excerpt: 'A comprehensive guide to weather patterns and the ideal time for your visit.',
        category: 'Travel Tips',
        categoryId: null,
        date: 'Dec 15, 2025',
        readTime: 4,
        image: 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=800&q=80'
    }
];

// Fetch blog categories from API
const fetchCategories = async () => {
    try {
        const response = await window.axios.get('/api/frontend/blog-categories');
        const data = response.data;
        if (data.success && data.data) {
            categories.value = data.data;
        }
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
};

// Fetch blog posts from API
const fetchPosts = async () => {
    loading.value = true;
    try {
        const response = await window.axios.get('/api/frontend/blog-posts');
        const data = response.data;
        
        if (data.success && data.data && data.data.length > 0) {
            posts.value = data.data.map(post => ({
                id: post.id,
                title: post.title,
                slug: post.slug || `post-${post.id}`, // Defensive fallback
                excerpt: post.excerpt,
                category: post.category?.name || 'General',
                categoryId: post.category_id || post.category?.id || null,
                date: post.published_at,
                readTime: parseInt(post.readTime) || parseInt(post.read_time) || 5,
                image: post.image
            }));
        } else {
            posts.value = [];
        }
    } catch (error) {
        console.error('Error fetching posts:', error);
        posts.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(async () => {
    await Promise.all([fetchCategories(), fetchPosts()]);
});
</script>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
