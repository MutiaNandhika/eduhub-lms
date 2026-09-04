<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import { NotificationItem } from '@/Types';
import Dropdown from '@/Components/UI/Dropdown.vue';
import { Bell, CheckCheck, Award, BookOpen, CheckCircle, AlertCircle } from 'lucide-vue-next';

interface Props {
    unreadCount: number;
}

defineProps<Props>();

const notifications = ref<NotificationItem[]>([]);
const loading = ref(false);

const fetchNotifications = async () => {
    loading.value = true;
    try {
        const res = await fetch('/api/notifications');
        if (res.ok) {
            const data = await res.json();
            notifications.value = data;
        }
    } catch (_) {
    } finally {
        loading.value = false;
    }
};

const markAsRead = (id: number) => {
    router.post(`/notifications/${id}/read`, {}, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            const item = notifications.value.find((n) => n.id === id);
            if (item) item.read_at = new Date().toISOString();
        },
    });
};

const markAllAsRead = () => {
    router.post('/notifications/read-all', {}, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            notifications.value.forEach((n) => (n.read_at = new Date().toISOString()));
        },
    });
};

const formatDate = (dateStr: string) => {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
};
</script>

<template>
    <Dropdown align="right" width="80">
        <template #trigger>
            <button
                type="button"
                @click="fetchNotifications"
                class="relative p-2 rounded-xl text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800/80 transition-colors select-none cursor-pointer"
            >
                <Bell class="w-5 h-5" />
                <span
                    v-if="unreadCount > 0"
                    class="absolute top-1 right-1 w-4 h-4 rounded-full bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center animate-pulse"
                >
                    {{ unreadCount > 9 ? '9+' : unreadCount }}
                </span>
            </button>
        </template>

        <template #content>
            <div class="p-3.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    Notifications
                </span>
                <button
                    v-if="unreadCount > 0"
                    type="button"
                    @click="markAllAsRead"
                    class="text-xs text-brand-600 dark:text-brand-400 hover:underline font-semibold flex items-center gap-1"
                >
                    <CheckCheck class="w-3.5 h-3.5" />
                    Mark all read
                </button>
            </div>

            <div class="max-h-80 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800/60">
                <div v-if="notifications.length === 0" class="p-6 text-center text-xs text-slate-400">
                    No notifications yet
                </div>

                <div
                    v-for="item in notifications"
                    :key="item.id"
                    :class="[
                        'p-3.5 flex items-start gap-3 transition-colors text-left',
                        !item.read_at ? 'bg-brand-50/40 dark:bg-brand-950/20' : 'hover:bg-slate-50 dark:hover:bg-slate-800/40',
                    ]"
                >
                    <div class="mt-0.5 shrink-0">
                        <Award v-if="item.type === 'certificate_issued'" class="w-4 h-4 text-amber-500" />
                        <CheckCircle v-else-if="item.type === 'course_completed'" class="w-4 h-4 text-emerald-500" />
                        <BookOpen v-else-if="item.type === 'course_enrolled'" class="w-4 h-4 text-brand-500" />
                        <AlertCircle v-else class="w-4 h-4 text-sky-500" />
                    </div>

                    <div class="flex-1 min-w-0" @click="!item.read_at ? markAsRead(item.id) : null">
                        <h5 class="text-xs font-bold text-slate-800 dark:text-slate-200">
                            {{ item.title }}
                        </h5>
                        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mt-0.5">
                            {{ item.message }}
                        </p>
                        <span class="text-[10px] text-slate-400 mt-1 block">
                            {{ formatDate(item.created_at) }}
                        </span>
                    </div>

                    <button
                        v-if="!item.read_at"
                        type="button"
                        @click="markAsRead(item.id)"
                        class="w-2 h-2 rounded-full bg-brand-600 mt-1.5 shrink-0"
                        title="Mark as read"
                    />
                </div>
            </div>
        </template>
    </Dropdown>
</template>
