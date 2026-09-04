<script setup lang="ts">
import { HelpCircle } from 'lucide-vue-next';

interface Props {
    title: string;
    description?: string;
    actionLabel?: string;
}

defineProps<Props>();

const emit = defineEmits<{
    (e: 'action'): void;
}>();
</script>

<template>
    <div class="text-center py-12 px-4 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-800 bg-white/50 dark:bg-slate-900/30">
        <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-brand-50 dark:bg-brand-950/50 border border-brand-100 dark:border-brand-900/50 flex items-center justify-center text-brand-600 dark:text-brand-400">
            <slot name="icon">
                <HelpCircle class="w-7 h-7" />
            </slot>
        </div>

        <h3 class="text-base font-bold text-slate-900 dark:text-white">
            {{ title }}
        </h3>

        <p v-if="description" class="mt-1 text-sm text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
            {{ description }}
        </p>

        <div v-if="$slots.action || actionLabel" class="mt-6 flex justify-center">
            <slot name="action">
                <button
                    v-if="actionLabel"
                    type="button"
                    @click="emit('action')"
                    class="px-4 py-2 text-sm font-semibold rounded-lg bg-brand-600 hover:bg-brand-700 text-white transition-colors"
                >
                    {{ actionLabel }}
                </button>
            </slot>
        </div>
    </div>
</template>
