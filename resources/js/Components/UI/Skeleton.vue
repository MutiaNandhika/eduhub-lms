<script setup lang="ts">
interface Props {
    className?: string;
}

defineProps<Props>();
</script>

<template>
    <div :class="['animate-pulse bg-slate-200 dark:bg-slate-800 rounded-lg', className || 'h-4 w-full']" />
</template>
