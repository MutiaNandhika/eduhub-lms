<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    src?: string | null;
    name?: string;
    size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl';
}

const props = withDefaults(defineProps<Props>(), {
    size: 'md',
});

const initials = computed(() => {
    if (!props.name) return 'U';
    const parts = props.name.trim().split(/\s+/);
    if (parts.length === 1) return parts[0].substring(0, 2).toUpperCase();
    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
});

const sizeClasses = computed(() => {
    switch (props.size) {
        case 'xs':
            return 'w-6 h-6 text-xs';
        case 'sm':
            return 'w-8 h-8 text-xs';
        case 'md':
            return 'w-10 h-10 text-sm';
        case 'lg':
            return 'w-12 h-12 text-base';
        case 'xl':
            return 'w-16 h-16 text-lg';
        default:
            return 'w-10 h-10 text-sm';
    }
});
</script>

<template>
    <div
        :class="[
            'relative inline-flex items-center justify-center shrink-0 rounded-full font-bold overflow-hidden select-none',
            sizeClasses,
            src ? 'bg-slate-200 dark:bg-slate-800' : 'bg-brand-100 text-brand-700 dark:bg-brand-950 dark:text-brand-300 border border-brand-200 dark:border-brand-800',
        ]"
    >
        <img
            v-if="src"
            :src="src"
            :alt="name || 'User Avatar'"
            class="w-full h-full object-cover"
            @error="(e: any) => e.target.style.display = 'none'"
        />
        <span v-else>{{ initials }}</span>
    </div>
</template>
