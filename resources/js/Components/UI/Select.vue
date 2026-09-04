<script setup lang="ts">
import { computed } from 'vue';

interface Option {
    value: string | number;
    label: string;
}

interface Props {
    id?: string;
    label?: string;
    modelValue?: string | number | null;
    options?: Option[];
    placeholder?: string;
    error?: string;
    hint?: string;
    required?: boolean;
    disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    options: () => [],
    required: false,
    disabled: false,
    placeholder: '',
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: any): void;
}>();

const selectId = computed(() => props.id || (props.label ? `select-${props.label.toLowerCase().replace(/\s+/g, '-')}` : undefined));

const handleChange = (e: Event) => {
    const target = e.target as HTMLSelectElement;
    emit('update:modelValue', target.value);
};
</script>

<template>
    <div class="w-full space-y-1.5">
        <label
            v-if="label"
            :for="selectId"
            class="block text-sm font-medium text-slate-700 dark:text-slate-300"
        >
            {{ label }}
            <span v-if="required" class="text-rose-500">*</span>
        </label>

        <select
            :id="selectId"
            :value="modelValue ?? ''"
            :required="required"
            :disabled="disabled"
            @change="handleChange"
            :class="[
                'block w-full rounded-lg border text-sm py-2.5 px-3.5 transition-colors focus:ring-2 focus:ring-brand-500 focus:outline-none dark:bg-slate-900 dark:text-slate-100 disabled:opacity-50 disabled:bg-slate-100 dark:disabled:bg-slate-800',
                error
                    ? 'border-rose-300 text-rose-900 focus:border-rose-500 focus:ring-rose-500 dark:border-rose-700 dark:text-rose-300'
                    : 'border-slate-300 dark:border-slate-700 text-slate-900 focus:border-brand-500',
            ]"
        >
            <option v-if="placeholder" value="" disabled selected>
                {{ placeholder }}
            </option>
            <option
                v-for="opt in options"
                :key="opt.value"
                :value="opt.value"
            >
                {{ opt.label }}
            </option>
            <slot />
        </select>

        <p v-if="error" class="text-xs text-rose-600 dark:text-rose-400 font-medium">
            {{ error }}
        </p>
        <p v-else-if="hint" class="text-xs text-slate-500 dark:text-slate-400">
            {{ hint }}
        </p>
    </div>
</template>
