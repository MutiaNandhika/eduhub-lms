<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    id?: string;
    label?: string;
    description?: string;
    modelValue?: boolean;
    disabled?: boolean;
    error?: string;
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: false,
    disabled: false,
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
}>();

const checkboxId = computed(() => props.id || (props.label ? `checkbox-${props.label.toLowerCase().replace(/\s+/g, '-')}` : undefined));

const handleChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    emit('update:modelValue', target.checked);
};
</script>

<template>
    <div class="flex items-start gap-3">
        <div class="flex items-center h-5">
            <input
                :id="checkboxId"
                type="checkbox"
                :checked="modelValue"
                :disabled="disabled"
                @change="handleChange"
                class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-500 dark:bg-slate-900 disabled:opacity-50 transition-colors cursor-pointer"
            />
        </div>
        <div class="text-sm">
            <label
                v-if="label"
                :for="checkboxId"
                class="font-medium text-slate-700 dark:text-slate-300 select-none cursor-pointer"
            >
                {{ label }}
            </label>
            <p v-if="description" class="text-slate-500 dark:text-slate-400 text-xs">
                {{ description }}
            </p>
            <p v-if="error" class="text-rose-600 dark:text-rose-400 text-xs font-medium mt-0.5">
                {{ error }}
            </p>
        </div>
    </div>
</template>
