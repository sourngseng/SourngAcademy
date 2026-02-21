import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';
import App from './frontend/App.vue';
import { initTranslations, t } from './frontend/composables/useTranslation';

// Import PrimeVue styles
import 'primeicons/primeicons.css';

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';

// Import pages
import Home from './frontend/pages/Home.vue';
import ProgramsList from './frontend/pages/ProgramsList.vue';
import Blog from './frontend/pages/Blog.vue';
import BlogPostDetail from './frontend/pages/BlogPostDetail.vue';
import About from './frontend/pages/About.vue';
import Contact from './frontend/pages/Contact.vue';
import Login from './frontend/pages/Login.vue';
import Register from './frontend/pages/Register.vue';
import Page from './frontend/pages/Page.vue';
import FacultyDetail from './frontend/pages/FacultyDetail.vue';
import DepartmentDetail from './frontend/pages/DepartmentDetail.vue';
import CampusDetail from './frontend/pages/CampusDetail.vue';
import CampusesList from './frontend/pages/CampusesList.vue';

// Create router
const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', name: 'home', component: Home },
        { path: '/home', redirect: '/' },
        { path: '/programs', name: 'programs', component: ProgramsList },
        { path: '/faculties/:slug', name: 'FacultyDetail', component: FacultyDetail },
        { path: '/departments/:slug', name: 'DepartmentDetail', component: DepartmentDetail },
        { path: '/campuses', name: 'CampusesList', component: CampusesList },
        { path: '/campuses/:slug', name: 'CampusDetail', component: CampusDetail },
        { path: '/blog', name: 'blog', component: Blog },
        { path: '/blog/:slug', name: 'blog-post', component: BlogPostDetail },
        { path: '/about', name: 'about', component: About },
        { path: '/contact', name: 'contact', component: Contact },
        { path: '/login', name: 'login', component: Login },
        { path: '/register', name: 'register', component: Register },
        { path: '/page/:slug', name: 'page', component: Page }
    ],
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else {
            return { top: 0 };
        }
    }
});

// Create and mount app
const app = createApp(App);

// Add global translation function
app.config.globalProperties.$t = t;

app.use(router);
app.use(PrimeVue, {
    theme: {
        preset: Aura,
        options: {
            darkModeSelector: '.dark-mode',
            cssLayer: false
        }
    }
});

// Initialize translations before mounting
initTranslations().then(() => {
    app.mount('#app');
});
