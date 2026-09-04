<script setup lang="ts">
import Modal from './Modal.vue';
import Button from './Button.vue';
import { AlertTriangle } from 'lucide-vue-next';

interface Props {
    show: boolean;
    title?: string;
    message?: string;
    confirmText?: string;
    cancelText?: string;
    variant?: 'danger' | 'primary' | 'warning';
    loading?: boolean;
}

withDefaults(defineProps<Props>(), {
    title: 'Confirm Action',
    message: 'Are you sure you want to perform this action? This action cannot be undone.',
    confirmText: 'Confirm',
    cancelText: 'Cancel',
    variant: 'danger',
    loading: false,
});

const emit = defineEmits<{
    (e: 'confirm'): void;
    (e: 'close'): void;
}>();
</script>

<template>
    <Modal :show="show" max-width="md" @close="emit('close')">
        <div class="flex items-start gap-4">
            <div
                :class="[
                    'w-11 h-11 rounded-2xl flex items-center justify-center shrink-0',
                    variant === 'danger' ? 'bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400' : 'bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400',
                ]"
            >
                <AlertTriangle class="w-6 h-6" />
            </div>

            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">
                    {{ title }}
                </h3>
                <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                    {{ message }}
                </p>
            </div>
        </div>

        <template #footer>
            <Button variant="outline" size="sm" @click="emit('close')">
                {{ cancelText }}
            </Button>
            <Button
                :variant="variant"
                size="sm"
                :loading="loading"
                @click="emit('confirm')"
            >
                {{ confirmText }}
            </Button>
        </template>
    </Modal>
</template>
