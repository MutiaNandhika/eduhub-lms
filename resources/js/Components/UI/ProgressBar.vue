<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    value: number;
    showLabel?: boolean;
    size?: 'xs' | 'sm' | 'md' | 'lg';
    variant?: 'brand' | 'success' | 'warning' | 'danger';
}

const props = withDefaults(defineProps<Props>(), {
    showLabel: false,
    size: 'sm',
    variant: 'brand',
});

const clampedValue = computed(() => Math.min(100, Math.max(0, Math.round(props.value))));

const heightClass = computed(() => {
    switch (props.size) {
        case 'xs': return 'h-1.5';
        case 'sm': return 'h-2';
        case 'md': return 'h-3';
        case 'lg': return 'h-4';
        default: return 'h-2';
    }
});

const colorClass = computed(() => {
    switch (props.variant) {
        case 'success': return 'bg-emerald-500';
        case 'warning': return 'bg-amber-500';
        case 'danger': return 'bg-rose-500';
        case 'brand':
        default:
            return 'bg-brand-600';
    }
});
</script>

<template>
    <div class="w-full">
        <div v-if="showLabel" class="flex justify-between items-center text-xs font-semibold mb-1.5">
            <span class="text-slate-600 dark:text-slate-400">Progress</span>
            <span class="text-slate-900 dark:text-white">{{ clampedValue }}%</span>
        </div>
        <div :class="['w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden', heightClass]">
            <div
                :class="['transition-all duration-500 rounded-full', colorClass, heightClass]"
                :style="{ width: `${clampedValue}%` }"
            />
        </div>
    </div>
</template>
