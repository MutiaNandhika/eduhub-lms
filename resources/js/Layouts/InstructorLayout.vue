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
    LayoutDashboard,
    Layers,
    PlusCircle,
    BarChart3,
    ArrowLeft,
    LogOut,
    Menu,
    X,
    ChevronDown,
} from 'lucide-vue-next';

interface Props {
    title?: string;
}

defineProps<Props>();

const page = usePage<SharedProps>();
const { success, error, warning, info } = useToast();
const sidebarOpen = ref(false);

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
    <div class="min-h-screen flex bg-[#f8faf9] dark:bg-[#08131a] text-slate-800 dark:text-slate-100 transition-colors">
        <Toast />

        <!-- Sidebar Mobile Backdrop -->
        <div
            v-if="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-midnight-950/60 backdrop-blur-sm lg:hidden"
        />

        <!-- Desktop & Mobile Sidebar -->
        <aside
            :class="[
                'fixed lg:static inset-y-0 left-0 z-50 w-64 bg-white dark:bg-midnight-900 border-r border-slate-200/80 dark:border-white/5 flex flex-col justify-between transition-transform duration-200 lg:translate-x-0',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full',
            ]"
        >
            <div>
                <!-- Brand Header -->
                <div class="h-20 px-6 flex items-center justify-between border-b border-slate-100 dark:border-white/5">
                    <div>
                        <Logo size="sm" />
                        <span class="text-[9px] font-extrabold text-brand-600 dark:text-brand-400 uppercase tracking-widest block pl-10 -mt-1">Instructor Studio</span>
                    </div>

                    <button
                        type="button"
                        @click="sidebarOpen = false"
                        class="lg:hidden p-1 rounded-lg text-slate-400 hover:text-slate-600"
                    >
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <!-- Nav Items -->
                <div class="p-4 space-y-1">
                    <Link
                        href="/instructor/dashboard"
                        :class="[
                            'flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-colors',
                            $page.url === '/instructor/dashboard'
                                ? 'bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300'
                                : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white',
                        ]"
                    >
                        <LayoutDashboard class="w-4 h-4" />
                        Dashboard
                    </Link>

                    <Link
                        href="/instructor/courses"
                        :class="[
                            'flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-colors',
                            $page.url === '/instructor/courses' || ($page.url.startsWith('/instructor/courses') && !$page.url.includes('/create'))
                                ? 'bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300'
                                : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white',
                        ]"
                    >
                        <Layers class="w-4 h-4" />
                        My Courses
                    </Link>

                    <Link
                        href="/instructor/courses/create"
                        :class="[
                            'flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-colors',
                            $page.url === '/instructor/courses/create'
                                ? 'bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300'
                                : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white',
                        ]"
                    >
                        <PlusCircle class="w-4 h-4 text-emerald-500" />
                        Create New Course
                    </Link>

                    <Link
                        href="/instructor/analytics"
                        :class="[
                            'flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-colors',
                            $page.url.startsWith('/instructor/analytics')
                                ? 'bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300'
                                : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white',
                        ]"
                    >
                        <BarChart3 class="w-4 h-4" />
                        Performance Analytics
                    </Link>
                </div>
            </div>

            <!-- Footer Quick Links -->
            <div class="p-4 border-t border-slate-100 dark:border-slate-800 space-y-1">
                <Link
                    href="/dashboard"
                    class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                >
                    <ArrowLeft class="w-4 h-4" />
                    Switch to Student View
                </Link>

                <button
                    type="button"
                    @click="logout"
                    class="w-full text-left flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors"
                >
                    <LogOut class="w-4 h-4" />
                    Sign Out
                </button>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Header Bar -->
            <header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800/80 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        @click="sidebarOpen = true"
                        class="lg:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    >
                        <Menu class="w-5 h-5" />
                    </button>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                        {{ title || 'Instructor Studio' }}
                    </h2>
                </div>

                <div class="flex items-center gap-3">
                    <ThemeToggle />
                    <NotificationDropdown :unread-count="$page.props.auth.unreadNotificationsCount" />
                    <div class="flex items-center gap-2.5 pl-2 border-l border-slate-200 dark:border-slate-800">
                        <Avatar
                            :src="$page.props.auth.user.avatar"
                            :name="$page.props.auth.user.name"
                            size="sm"
                        />
                        <div class="hidden sm:block text-left">
                            <span class="block text-xs font-bold text-slate-900 dark:text-white truncate max-w-[120px]">
                                {{ $page.props.auth.user.name }}
                            </span>
                            <span class="block text-[10px] text-slate-400 capitalize">Instructor</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Body -->
            <main class="flex-1 p-4 sm:p-8 max-w-7xl w-full mx-auto">
                <slot />
            </main>
        </div>
    </div>
</template>
