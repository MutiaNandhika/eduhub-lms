<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    id?: string;
    label?: string;
    modelValue?: string | null;
    placeholder?: string;
    rows?: number;
    error?: string;
    hint?: string;
    required?: boolean;
    disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    rows: 4,
    required: false,
    disabled: false,
    placeholder: '',
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const textareaId = computed(() => props.id || (props.label ? `textarea-${props.label.toLowerCase().replace(/\s+/g, '-')}` : undefined));

const handleInput = (e: Event) => {
    const target = e.target as HTMLTextAreaElement;
    emit('update:modelValue', target.value);
};
</script>

<template>
    <div class="w-full space-y-1.5">
        <label
            v-if="label"
            :for="textareaId"
            class="block text-sm font-medium text-slate-700 dark:text-slate-300"
        >
            {{ label }}
            <span v-if="required" class="text-rose-500">*</span>
        </label>

        <textarea
            :id="textareaId"
            :rows="rows"
            :value="modelValue ?? ''"
            :placeholder="placeholder"
            :required="required"
            :disabled="disabled"
            @input="handleInput"
            :class="[
                'block w-full rounded-lg border text-sm py-2.5 px-3.5 transition-colors focus:ring-2 focus:ring-brand-500 focus:outline-none dark:bg-slate-900 dark:text-slate-100 disabled:opacity-50 disabled:bg-slate-100 dark:disabled:bg-slate-800',
                error
                    ? 'border-rose-300 text-rose-900 placeholder-rose-300 focus:border-rose-500 focus:ring-rose-500 dark:border-rose-700 dark:text-rose-300'
                    : 'border-slate-300 dark:border-slate-700 text-slate-900 placeholder-slate-400 focus:border-brand-500',
            ]"
        />

        <p v-if="error" class="text-xs text-rose-600 dark:text-rose-400 font-medium">
            {{ error }}
        </p>
        <p v-else-if="hint" class="text-xs text-slate-500 dark:text-slate-400">
            {{ hint }}
        </p>
    </div>
</template>
