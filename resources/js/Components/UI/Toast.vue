<script setup lang="ts">
import { useToast } from '@/Composables/useToast';
import { CheckCircle2, AlertCircle, AlertTriangle, Info, X } from 'lucide-vue-next';

const { toasts, removeToast } = useToast();
</script>

<template>
    <div class="fixed bottom-5 right-5 z-50 flex flex-col gap-2.5 max-w-md w-full pointer-events-none px-4 sm:px-0">
        <TransitionGroup
            enter-active-class="transform ease-out duration-300 transition"
            enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
            enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-for="toast in toasts"
                :key="toast.id"
                :class="[
                    'pointer-events-auto flex items-start gap-3 p-4 rounded-xl border shadow-xl backdrop-blur-md transition-all',
                    toast.type === 'success' ? 'bg-white/95 dark:bg-slate-900/95 border-emerald-200 dark:border-emerald-800/80 text-emerald-900 dark:text-emerald-100' : '',
                    toast.type === 'error' ? 'bg-white/95 dark:bg-slate-900/95 border-rose-200 dark:border-rose-800/80 text-rose-900 dark:text-rose-100' : '',
                    toast.type === 'warning' ? 'bg-white/95 dark:bg-slate-900/95 border-amber-200 dark:border-amber-800/80 text-amber-900 dark:text-amber-100' : '',
                    toast.type === 'info' ? 'bg-white/95 dark:bg-slate-900/95 border-sky-200 dark:border-sky-800/80 text-sky-900 dark:text-sky-100' : '',
                ]"
            >
                <div class="shrink-0 mt-0.5">
                    <CheckCircle2 v-if="toast.type === 'success'" class="w-5 h-5 text-emerald-500" />
                    <AlertCircle v-else-if="toast.type === 'error'" class="w-5 h-5 text-rose-500" />
                    <AlertTriangle v-else-if="toast.type === 'warning'" class="w-5 h-5 text-amber-500" />
                    <Info v-else class="w-5 h-5 text-sky-500" />
                </div>

                <div class="flex-1 min-w-0">
                    <h4 v-if="toast.title" class="text-sm font-bold">
                        {{ toast.title }}
                    </h4>
                    <p class="text-xs mt-0.5 opacity-90 leading-relaxed">
                        {{ toast.message }}
                    </p>
                </div>

                <button
                    @click="removeToast(toast.id)"
                    class="shrink-0 p-1 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 opacity-70 hover:opacity-100 transition-opacity"
                >
                    <X class="w-4 h-4" />
                </button>
            </div>
        </TransitionGroup>
    </div>
</template>
