<script setup lang="ts">
import { watch, onMounted, onUnmounted } from 'vue';
import { X } from 'lucide-vue-next';

interface Props {
    show: boolean;
    title?: string;
    maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl' | '4xl';
    closeable?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    show: false,
    maxWidth: 'lg',
    closeable: true,
});

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const closeOnEscape = (e: KeyboardEvent) => {
    if (e.key === 'Escape' && props.show && props.closeable) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

watch(
    () => props.show,
    (show) => {
        if (show) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }
);
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center"
            >
                <!-- Backdrop -->
                <div
                    class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity"
                    @click="close"
                />

                <!-- Dialog Panel -->
                <Transition
                    enter-active-class="ease-out duration-200"
                    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-150"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <div
                        v-if="show"
                        :class="[
                            'relative bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl border border-slate-200 dark:border-slate-800 w-full transform transition-all',
                            {
                                'sm:max-w-sm': maxWidth === 'sm',
                                'sm:max-w-md': maxWidth === 'md',
                                'sm:max-w-lg': maxWidth === 'lg',
                                'sm:max-w-xl': maxWidth === 'xl',
                                'sm:max-w-2xl': maxWidth === '2xl',
                                'sm:max-w-4xl': maxWidth === '4xl',
                            },
                        ]"
                    >
                        <!-- Header -->
                        <div
                            v-if="title || closeable"
                            class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between"
                        >
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                                <slot name="title">{{ title }}</slot>
                            </h3>
                            <button
                                v-if="closeable"
                                @click="close"
                                class="p-1 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                            >
                                <X class="w-5 h-5" />
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="px-6 py-5">
                            <slot />
                        </div>

                        <!-- Footer -->
                        <div
                            v-if="$slots.footer"
                            class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3"
                        >
                            <slot name="footer" />
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
