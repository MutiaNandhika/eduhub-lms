<script setup lang="ts">
interface Tab {
    id: string | number;
    label: string;
    count?: number;
    icon?: any;
}

interface Props {
    tabs: Tab[];
    modelValue: string | number;
}

defineProps<Props>();

const emit = defineEmits<{
    (e: 'update:modelValue', id: string | number): void;
}>();
</script>

<template>
    <div class="border-b border-slate-200 dark:border-slate-800">
        <nav class="-mb-px flex space-x-6 overflow-x-auto pb-1" aria-label="Tabs">
            <button
                v-for="tab in tabs"
                :key="tab.id"
                type="button"
                @click="emit('update:modelValue', tab.id)"
                :class="[
                    'whitespace-nowrap pb-3.5 px-1 border-b-2 font-semibold text-sm transition-all flex items-center gap-2 cursor-pointer',
                    modelValue === tab.id
                        ? 'border-brand-600 text-brand-600 dark:text-brand-400 dark:border-brand-400'
                        : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-700',
                ]"
            >
                <component :is="tab.icon" v-if="tab.icon" class="w-4 h-4" />
                <span>{{ tab.label }}</span>
                <span
                    v-if="tab.count !== undefined"
                    :class="[
                        'text-xs py-0.5 px-2 rounded-full font-bold',
                        modelValue === tab.id
                            ? 'bg-brand-100 text-brand-700 dark:bg-brand-950 dark:text-brand-300'
                            : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400',
                    ]"
                >
                    {{ tab.count }}
                </span>
            </button>
        </nav>
    </div>
</template>
