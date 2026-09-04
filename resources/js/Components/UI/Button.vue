<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Loader2 } from 'lucide-vue-next';

interface Props {
    variant?: 'primary' | 'secondary' | 'outline' | 'ghost' | 'danger' | 'success';
    size?: 'xs' | 'sm' | 'md' | 'lg';
    type?: 'button' | 'submit' | 'reset';
    href?: string;
    disabled?: boolean;
    loading?: boolean;
    fullWidth?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'primary',
    size: 'md',
    type: 'button',
    disabled: false,
    loading: false,
    fullWidth: false,
});

const variantClasses = computed(() => {
    switch (props.variant) {
        case 'primary':
            return 'bg-brand-600 hover:bg-brand-700 text-white shadow-sm hover:shadow active:bg-brand-800 focus:ring-brand-500';
        case 'secondary':
            return 'bg-slate-800 hover:bg-slate-900 text-white dark:bg-slate-700 dark:hover:bg-slate-600 focus:ring-slate-500';
        case 'outline':
            return 'border border-slate-300 dark:border-slate-700 bg-transparent hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 focus:ring-slate-400';
        case 'ghost':
            return 'bg-transparent hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 focus:ring-slate-400';
        case 'danger':
            return 'bg-rose-600 hover:bg-rose-700 text-white shadow-sm focus:ring-rose-500';
        case 'success':
            return 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm focus:ring-emerald-500';
        default:
            return 'bg-brand-600 hover:bg-brand-700 text-white focus:ring-brand-500';
    }
});

const sizeClasses = computed(() => {
    switch (props.size) {
        case 'xs':
            return 'px-2.5 py-1.5 text-xs rounded-md gap-1.5 font-medium';
        case 'sm':
            return 'px-3 py-1.5 text-sm rounded-lg gap-1.5 font-medium';
        case 'md':
            return 'px-4 py-2.5 text-sm rounded-lg gap-2 font-semibold';
        case 'lg':
            return 'px-6 py-3.5 text-base rounded-xl gap-2.5 font-semibold';
        default:
            return 'px-4 py-2.5 text-sm rounded-lg gap-2 font-semibold';
    }
});
</script>

<template>
    <Link
        v-if="href && !disabled"
        :href="href"
        :class="[
            'inline-flex items-center justify-center transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-900 select-none cursor-pointer',
            variantClasses,
            sizeClasses,
            fullWidth ? 'w-full' : '',
            disabled || loading ? 'opacity-60 cursor-not-allowed pointer-events-none' : '',
        ]"
    >
        <Loader2 v-if="loading" class="w-4 h-4 animate-spin shrink-0" />
        <slot name="icon-left" />
        <slot />
        <slot name="icon-right" />
    </Link>

    <button
        v-else
        :type="type"
        :disabled="disabled || loading"
        :class="[
            'inline-flex items-center justify-center transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-900 select-none cursor-pointer',
            variantClasses,
            sizeClasses,
            fullWidth ? 'w-full' : '',
            disabled || loading ? 'opacity-60 cursor-not-allowed' : '',
        ]"
    >
        <Loader2 v-if="loading" class="w-4 h-4 animate-spin shrink-0" />
        <slot name="icon-left" />
        <slot />
        <slot name="icon-right" />
    </button>
</template>
