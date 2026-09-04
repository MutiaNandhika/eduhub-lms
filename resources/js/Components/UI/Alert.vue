<script setup lang="ts">
import { AlertCircle, CheckCircle2, Info, AlertTriangle, X } from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
    type?: 'info' | 'success' | 'warning' | 'error';
    title?: string;
    dismissible?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'info',
    dismissible: false,
});

const emit = defineEmits<{
    (e: 'dismiss'): void;
}>();

const variantClasses = computed(() => {
    switch (props.type) {
        case 'success':
            return 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200';
        case 'warning':
            return 'bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200';
        case 'error':
            return 'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200';
        case 'info':
        default:
            return 'bg-sky-50 dark:bg-sky-950/40 border-sky-200 dark:border-sky-800 text-sky-800 dark:text-sky-200';
    }
});
</script>

<template>
    <div :class="['flex items-start gap-3.5 p-4 rounded-xl border text-sm', variantClasses]">
        <div class="shrink-0 mt-0.5">
            <CheckCircle2 v-if="type === 'success'" class="w-5 h-5 text-emerald-500" />
            <AlertCircle v-else-if="type === 'error'" class="w-5 h-5 text-rose-500" />
            <AlertTriangle v-else-if="type === 'warning'" class="w-5 h-5 text-amber-500" />
            <Info v-else class="w-5 h-5 text-sky-500" />
        </div>

        <div class="flex-1 min-w-0">
            <h5 v-if="title" class="font-bold mb-0.5">
                {{ title }}
            </h5>
            <div class="opacity-90 leading-relaxed text-xs sm:text-sm">
                <slot />
            </div>
        </div>

        <button
            v-if="dismissible"
            @click="emit('dismiss')"
            class="shrink-0 p-1 rounded-md hover:bg-black/5 dark:hover:bg-white/10 opacity-70 hover:opacity-100 transition-opacity"
        >
            <X class="w-4 h-4" />
        </button>
    </div>
</template>
