<script setup lang="ts">
interface Props {
    hoverable?: boolean;
    padded?: boolean;
    elevated?: boolean;
}

withDefaults(defineProps<Props>(), {
    hoverable: false,
    padded: true,
    elevated: false,
});
</script>

<template>
    <div
        :class="[
            'rounded-2xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-slate-900 transition-all duration-200',
            elevated ? 'shadow-lg shadow-slate-200/50 dark:shadow-none' : 'shadow-sm',
            hoverable ? 'hover:shadow-md hover:border-slate-300 dark:hover:border-slate-700' : '',
            padded ? 'p-6' : '',
        ]"
    >
        <div v-if="$slots.header" class="border-b border-slate-100 dark:border-slate-800 pb-4 mb-5">
            <slot name="header" />
        </div>

        <slot />

        <div v-if="$slots.footer" class="border-t border-slate-100 dark:border-slate-800 pt-4 mt-5">
            <slot name="footer" />
        </div>
    </div>
</template>
