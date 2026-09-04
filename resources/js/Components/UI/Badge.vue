<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    variant?: 'brand' | 'accent' | 'success' | 'warning' | 'danger' | 'neutral' | 'info' | 'dark' | 'coral';
    size?: 'sm' | 'md' | 'lg';
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'brand',
    size: 'md',
});

const variantClasses = computed(() => {
    switch (props.variant) {
        case 'brand':
            return 'bg-brand-50 text-brand-800 dark:bg-brand-950/80 dark:text-brand-300 border border-brand-200/80 dark:border-brand-800/80';
        case 'coral':
        case 'accent':
            return 'bg-coral-50 text-coral-800 dark:bg-coral-950/80 dark:text-coral-300 border border-coral-200/80 dark:border-coral-800/80';
        case 'success':
            return 'bg-emerald-50 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-800/80';
        case 'warning':
            return 'bg-amber-50 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border border-amber-200/80 dark:border-amber-800/80';
        case 'danger':
            return 'bg-rose-50 text-rose-800 dark:bg-rose-950/80 dark:text-rose-300 border border-rose-200/80 dark:border-rose-800/80';
        case 'info':
            return 'bg-sky-50 text-sky-800 dark:bg-sky-950/80 dark:text-sky-300 border border-sky-200/80 dark:border-sky-800/80';
        case 'dark':
            return 'bg-slate-900/85 text-white dark:bg-black/80 dark:text-white backdrop-blur-md border border-white/20 shadow-sm';
        case 'neutral':
        default:
            return 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700';
    }
});

const sizeClasses = computed(() => {
    switch (props.size) {
        case 'sm':
            return 'px-2.5 py-0.5 text-xs font-semibold';
        case 'lg':
            return 'px-3.5 py-1 text-sm font-bold';
        case 'md':
        default:
            return 'px-3 py-0.5 text-xs font-bold';
    }
});
</script>

<template>
    <span
        :class="[
            'inline-flex items-center gap-1.5 rounded-full transition-colors',
            variantClasses,
            sizeClasses,
        ]"
    >
        <slot name="icon" />
        <slot />
    </span>
</template>
