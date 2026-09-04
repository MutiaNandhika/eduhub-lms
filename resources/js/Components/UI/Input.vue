<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    id?: string;
    label?: string;
    modelValue?: string | number | null;
    type?: string;
    placeholder?: string;
    error?: string;
    hint?: string;
    required?: boolean;
    disabled?: boolean;
    autocomplete?: string;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'text',
    required: false,
    disabled: false,
    placeholder: '',
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const inputId = computed(() => props.id || (props.label ? `input-${props.label.toLowerCase().replace(/\s+/g, '-')}` : undefined));

const handleInput = (e: Event) => {
    const target = e.target as HTMLInputElement;
    emit('update:modelValue', target.value);
};
</script>

<template>
    <div class="w-full space-y-1.5">
        <label
            v-if="label"
            :for="inputId"
            class="block text-sm font-medium text-slate-700 dark:text-slate-300"
        >
            {{ label }}
            <span v-if="required" class="text-rose-500">*</span>
        </label>

        <div class="relative rounded-lg shadow-sm">
            <div
                v-if="$slots.prefix"
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500"
            >
                <slot name="prefix" />
            </div>

            <input
                :id="inputId"
                :type="type"
                :value="modelValue ?? ''"
                :placeholder="placeholder"
                :required="required"
                :disabled="disabled"
                :autocomplete="autocomplete"
                @input="handleInput"
                :class="[
                    'block w-full rounded-lg border text-sm transition-colors focus:ring-2 focus:ring-brand-500 focus:outline-none dark:bg-slate-900 dark:text-slate-100 disabled:opacity-50 disabled:bg-slate-100 dark:disabled:bg-slate-800',
                    $slots.prefix ? 'pl-10' : 'pl-3.5',
                    $slots.suffix ? 'pr-10' : 'pr-3.5',
                    'py-2.5',
                    error
                        ? 'border-rose-300 text-rose-900 placeholder-rose-300 focus:border-rose-500 focus:ring-rose-500 dark:border-rose-700 dark:text-rose-300'
                        : 'border-slate-300 dark:border-slate-700 text-slate-900 placeholder-slate-400 focus:border-brand-500',
                ]"
            />

            <div
                v-if="$slots.suffix"
                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500"
            >
                <slot name="suffix" />
            </div>
        </div>

        <p v-if="error" class="text-xs text-rose-600 dark:text-rose-400 font-medium">
            {{ error }}
        </p>
        <p v-else-if="hint" class="text-xs text-slate-500 dark:text-slate-400">
            {{ hint }}
        </p>
    </div>
</template>
