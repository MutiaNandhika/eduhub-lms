<script setup lang="ts">
import { Loader2 } from 'lucide-vue-next';

interface Props {
    message?: string;
}

withDefaults(defineProps<Props>(), {
    message: 'Loading content...',
});
</script>

<template>
    <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
        <Loader2 class="w-9 h-9 text-brand-600 dark:text-brand-400 animate-spin" />
        <p class="mt-3 text-sm font-medium text-slate-500 dark:text-slate-400">
            {{ message }}
        </p>
    </div>
</template>
