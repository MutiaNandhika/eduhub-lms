<script setup lang="ts">
import { ref, watch } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { SharedProps } from '@/Types';
import ThemeToggle from '@/Components/Domain/ThemeToggle.vue';
import NotificationDropdown from '@/Components/Domain/NotificationDropdown.vue';
import Dropdown from '@/Components/UI/Dropdown.vue';
import Avatar from '@/Components/UI/Avatar.vue';
import Toast from '@/Components/UI/Toast.vue';
import { useToast } from '@/Composables/useToast';
import Logo from '@/Components/UI/Logo.vue';
import {
    Menu,
    X,
    LayoutDashboard,
    GraduationCap,
    Award,
    User as UserIcon,
    LogOut,
    Shield,
    PenTool,
    ChevronDown,
} from 'lucide-vue-next';

interface Props {
    title?: string;
}

defineProps<Props>();

const page = usePage<SharedProps>();
const { success, error, warning, info } = useToast();
const mobileMenuOpen = ref(false);

// Watch flash messages from Inertia
watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) success(flash.success);
        if (flash?.error) error(flash.error);
        if (flash?.warning) warning(flash.warning);
        if (flash?.info) info(flash.info);
    },
    { deep: true, immediate: true }
);

const logout = () => {
    router.post('/logout');
};
</script>

<template>
    <div class="min-h-screen flex flex-col bg-[#f8faf9] dark:bg-[#08131a] text-slate-800 dark:text-slate-100 transition-colors duration-200">
        <!-- Toast Notification Stack -->
        <Toast />

        <!-- Navigation Bar (Clean Minimalist Pill Header) -->
        <header class="sticky top-0 z-40 bg-white/90 dark:bg-midnight-900/90 backdrop-blur-md border-b border-slate-200/80 dark:border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20 gap-4">
                    <!-- Brand Logo & Main Nav -->
                    <div class="flex items-center gap-10">
                        <Logo size="md" />

                        <!-- Desktop Primary Nav Links (Minimalist Pill Style) -->
                        <nav class="hidden md:flex items-center gap-1 bg-slate-100/70 dark:bg-midnight-950/60 p-1.5 rounded-full border border-slate-200/60 dark:border-white/5">
                            <Link
                                href="/courses"
                                :class="[
                                    'px-4 py-2 rounded-full text-xs font-bold transition-all',
                                    $page.url.startsWith('/courses') && !$page.url.startsWith('/instructor')
                                        ? 'text-white bg-brand-600 shadow-sm'
                                        : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-white/80 dark:hover:bg-midnight-800/80',
                                ]"
                            >
                                Browse Courses
                            </Link>

                            <template v-if="$page.props.auth.user">
                                <Link
                                    href="/dashboard"
                                    :class="[
                                        'px-4 py-2 rounded-full text-xs font-bold transition-all',
                                        $page.url === '/dashboard'
                                            ? 'text-white bg-brand-600 shadow-sm'
                                            : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-white/80 dark:hover:bg-midnight-800/80',
                                    ]"
                                >
                                    Dashboard
                                </Link>

                                <Link
                                    href="/my-courses"
                                    :class="[
                                        'px-4 py-2 rounded-full text-xs font-bold transition-all',
                                        $page.url.startsWith('/my-courses')
                                            ? 'text-white bg-brand-600 shadow-sm'
                                            : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-white/80 dark:hover:bg-midnight-800/80',
                                    ]"
                                >
                                    My Learning
                                </Link>

                                <Link
                                    href="/certificates"
                                    :class="[
                                        'px-4 py-2 rounded-full text-xs font-bold transition-all',
                                        $page.url.startsWith('/certificates')
                                            ? 'text-white bg-brand-600 shadow-sm'
                                            : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-white/80 dark:hover:bg-midnight-800/80',
                                    ]"
                                >
                                    Certificates
                                </Link>
                            </template>
                        </nav>
                    </div>

                    <!-- Right Controls -->
                    <div class="flex items-center gap-3">
                        <!-- Theme Switcher -->
                        <ThemeToggle />

                        <!-- Authenticated Items -->
                        <template v-if="$page.props.auth.user">
                            <!-- Notifications -->
                            <NotificationDropdown :unread-count="$page.props.auth.unreadNotificationsCount" />

                            <!-- User Dropdown Menu -->
                            <Dropdown align="right" width="56">
                                <template #trigger>
                                    <button
                                        type="button"
                                        class="flex items-center gap-2.5 p-1.5 rounded-full hover:bg-slate-100 dark:hover:bg-midnight-800/80 transition-colors select-none cursor-pointer"
                                    >
                                        <Avatar
                                            :src="$page.props.auth.user.avatar"
                                            :name="$page.props.auth.user.name"
                                            size="sm"
                                        />
                                        <span class="hidden sm:block text-xs font-bold text-slate-700 dark:text-slate-200 max-w-[120px] truncate">
                                            {{ $page.props.auth.user.name }}
                                        </span>
                                        <ChevronDown class="w-3.5 h-3.5 text-slate-400" />
                                    </button>
                                </template>

                                <template #content>
                                    <div class="px-4 py-3 border-b border-slate-100 dark:border-white/5">
                                        <p class="text-xs font-bold text-slate-900 dark:text-white truncate">
                                            {{ $page.props.auth.user.name }}
                                        </p>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">
                                            {{ $page.props.auth.user.email }}
                                        </p>
                                        <span class="inline-block mt-1.5 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-brand-50 text-brand-700 dark:bg-brand-950 dark:text-brand-300">
                                            {{ $page.props.auth.user.role }}
                                        </span>
                                    </div>

                                    <div class="py-1">
                                        <Link
                                            v-if="$page.props.auth.user.role === 'admin'"
                                            href="/admin/dashboard"
                                            class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800"
                                        >
                                            <Shield class="w-4 h-4 text-brand-600" />
                                            Admin Portal
                                        </Link>

                                        <Link
                                            v-if="$page.props.auth.user.role === 'instructor' || $page.props.auth.user.role === 'admin'"
                                            href="/instructor/dashboard"
                                            class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800"
                                        >
                                            <PenTool class="w-4 h-4 text-brand-600" />
                                            Instructor Studio
                                        </Link>

                                        <Link
                                            href="/profile"
                                            class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800"
                                        >
                                            <UserIcon class="w-4 h-4" />
                                            Account Settings
                                        </Link>
                                    </div>

                                    <div class="border-t border-slate-100 dark:border-white/5 py-1">
                                        <button
                                            type="button"
                                            @click="logout"
                                            class="w-full text-left flex items-center gap-2 px-4 py-2 text-xs font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30"
                                        >
                                            <LogOut class="w-4 h-4" />
                                            Sign Out
                                        </button>
                                    </div>
                                </template>
                            </Dropdown>
                        </template>

                        <!-- Guest Items (Reference Pill CTA) -->
                        <template v-else>
                            <Link
                                href="/login"
                                class="px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 hover:text-brand-600 dark:hover:text-brand-400 transition-colors"
                            >
                                Sign In
                            </Link>
                            <Link
                                href="/register"
                                class="pill-btn-coral"
                            >
                                Get Started
                            </Link>
                        </template>

                        <!-- Mobile Menu Button -->
                        <button
                            type="button"
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="md:hidden p-2 rounded-xl text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800"
                        >
                            <X v-if="mobileMenuOpen" class="w-6 h-6" />
                            <Menu v-else class="w-6 h-6" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Drawer -->
            <div v-show="mobileMenuOpen" class="md:hidden border-t border-slate-200 dark:border-white/5 bg-white dark:bg-midnight-900 px-4 pt-2 pb-6 space-y-2">
                <Link
                    href="/courses"
                    @click="mobileMenuOpen = false"
                    class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-midnight-800"
                >
                    Browse Courses
                </Link>

                <template v-if="$page.props.auth.user">
                    <Link
                        href="/dashboard"
                        @click="mobileMenuOpen = false"
                        class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-midnight-800"
                    >
                        Student Dashboard
                    </Link>
                    <Link
                        href="/my-courses"
                        @click="mobileMenuOpen = false"
                        class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-midnight-800"
                    >
                        My Courses
                    </Link>
                    <Link
                        href="/certificates"
                        @click="mobileMenuOpen = false"
                        class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-midnight-800"
                    >
                        My Certificates
                    </Link>

                    <div v-if="$page.props.auth.user.role === 'instructor' || $page.props.auth.user.role === 'admin'" class="pt-2 border-t border-slate-100 dark:border-white/5">
                        <Link
                            href="/instructor/dashboard"
                            @click="mobileMenuOpen = false"
                            class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-brand-600 dark:text-brand-400 hover:bg-brand-50 dark:hover:bg-midnight-800"
                        >
                            Instructor Studio
                        </Link>
                    </div>

                    <div v-if="$page.props.auth.user.role === 'admin'" class="pt-1">
                        <Link
                            href="/admin/dashboard"
                            @click="mobileMenuOpen = false"
                            class="block px-3 py-2.5 rounded-xl text-sm font-semibold text-brand-600 dark:text-brand-400 hover:bg-brand-50 dark:hover:bg-midnight-800"
                        >
                            Admin Portal
                        </Link>
                    </div>
                </template>
            </div>
        </header>

        <!-- Main Page Content -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Global Footer -->
        <footer class="bg-white dark:bg-midnight-900 border-t border-slate-200/80 dark:border-white/5 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Brand Column -->
                    <div class="md:col-span-1 space-y-3">
                        <Logo size="md" />
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed pt-1">
                            "Learn. Practice. Grow." Next-generation platform for structured developer and tech skills mastery.
                        </p>
                        <div class="text-xs text-slate-400">
                            &copy; 2026 EduHub LMS. All rights reserved.
                        </div>
                    </div>

                    <!-- Column 2: Explore -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white mb-4">
                            Explore
                        </h4>
                        <ul class="space-y-2.5 text-xs text-slate-500 dark:text-slate-400 font-medium">
                            <li><Link href="/courses" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Course Catalog</Link></li>
                            <li><Link href="/courses?level=beginner" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Beginner Courses</Link></li>
                            <li><Link href="/courses?level=advanced" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Advanced Tracks</Link></li>
                            <li><Link href="/courses?price=free" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Free Masterclasses</Link></li>
                        </ul>
                    </div>

                    <!-- Column 3: Platform -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white mb-4">
                            Platform
                        </h4>
                        <ul class="space-y-2.5 text-xs text-slate-500 dark:text-slate-400 font-medium">
                            <li><Link href="/dashboard" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Student Dashboard</Link></li>
                            <li><Link href="/certificates" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Earned Certificates</Link></li>
                            <li><Link href="/instructor/courses/create" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Become an Instructor</Link></li>
                        </ul>
                    </div>

                    <!-- Column 4: Quick Demo Creds -->
                    <div class="p-4 rounded-3xl bg-slate-50 dark:bg-midnight-950 border border-slate-200 dark:border-white/5">
                        <h4 class="text-xs font-bold text-slate-900 dark:text-white mb-2">
                            Demo Credentials
                        </h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-2">Password: <code class="bg-slate-200 dark:bg-midnight-800 px-1 py-0.5 rounded font-mono font-bold text-slate-800 dark:text-slate-200">password</code></p>
                        <div class="space-y-1 text-[11px] font-mono text-slate-600 dark:text-slate-400">
                            <div>• admin@eduhub.test</div>
                            <div>• instructor@eduhub.test</div>
                            <div>• student@eduhub.test</div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
