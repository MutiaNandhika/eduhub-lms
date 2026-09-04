<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    title: string;
    value: string | number;
    description?: string;
    trend?: 'up' | 'down' | 'neutral';
    trendValue?: string;
    variant?: 'brand' | 'success' | 'warning' | 'info';
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'brand',
});

const iconContainerClasses = computed(() => {
    switch (props.variant) {
        case 'success':
            return 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400 border-emerald-200/60 dark:border-emerald-800/60';
        case 'warning':
            return 'bg-amber-50 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400 border-amber-200/60 dark:border-amber-800/60';
        case 'info':
            return 'bg-sky-50 text-sky-600 dark:bg-sky-950/60 dark:text-sky-400 border-sky-200/60 dark:border-sky-800/60';
        case 'brand':
        default:
            return 'bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-400 border-brand-200/60 dark:border-brand-800/60';
    }
});
</script>

<template>
    <div class="p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-slate-900 shadow-sm transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">{{ title }}</span>
            <div
                v-if="$slots.icon"
                :class="['w-11 h-11 rounded-xl flex items-center justify-center border shrink-0', iconContainerClasses]"
            >
                <slot name="icon" />
            </div>
        </div>

        <div class="mt-4 flex items-baseline justify-between">
            <span class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ value }}</span>

            <div v-if="trendValue" class="flex items-center gap-1 text-xs font-semibold">
                <span
                    :class="[
                        trend === 'up' ? 'text-emerald-600 dark:text-emerald-400' : '',
                        trend === 'down' ? 'text-rose-600 dark:text-rose-400' : '',
                        trend === 'neutral' ? 'text-slate-500 dark:text-slate-400' : '',
                    ]"
                >
                    {{ trendValue }}
                </span>
            </div>
        </div>

        <p v-if="description" class="mt-2 text-xs text-slate-500 dark:text-slate-400">
            {{ description }}
        </p>
    </div>
</template>
