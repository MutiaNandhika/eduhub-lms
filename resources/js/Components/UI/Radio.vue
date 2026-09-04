<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    id?: string;
    name?: string;
    value: any;
    label?: string;
    description?: string;
    modelValue: any;
    disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    disabled: false,
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: any): void;
}>();

const isChecked = computed(() => props.modelValue === props.value);
const radioId = computed(() => props.id || `radio-${props.name}-${props.value}`);

const handleChange = () => {
    emit('update:modelValue', props.value);
};
</script>

<template>
    <label
        :for="radioId"
        :class="[
            'flex items-start gap-3 p-3.5 rounded-xl border transition-all cursor-pointer select-none',
            isChecked
                ? 'border-brand-500 bg-brand-50/50 dark:bg-brand-950/20 ring-1 ring-brand-500'
                : 'border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-900/60',
            disabled ? 'opacity-50 cursor-not-allowed' : '',
        ]"
    >
        <div class="flex items-center h-5">
            <input
                :id="radioId"
                :name="name"
                type="radio"
                :value="value"
                :checked="isChecked"
                :disabled="disabled"
                @change="handleChange"
                class="w-4 h-4 text-brand-600 border-slate-300 dark:border-slate-700 focus:ring-brand-500 dark:bg-slate-900"
            />
        </div>
        <div class="text-sm">
            <div class="font-medium text-slate-800 dark:text-slate-200">
                <slot>{{ label }}</slot>
            </div>
            <p v-if="description" class="text-slate-500 dark:text-slate-400 text-xs mt-0.5">
                {{ description }}
            </p>
        </div>
    </label>
</template>
