<script setup lang="ts">
import { computed } from 'vue';
import { Star } from 'lucide-vue-next';

interface Props {
    modelValue?: number;
    readonly?: boolean;
    size?: 'sm' | 'md' | 'lg';
    showScore?: boolean;
    count?: number;
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: 5,
    readonly: true,
    size: 'sm',
    showScore: false,
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: number): void;
}>();

const sizeClasses = computed(() => {
    switch (props.size) {
        case 'sm': return 'w-4 h-4';
        case 'md': return 'w-5 h-5';
        case 'lg': return 'w-6 h-6';
        default: return 'w-4 h-4';
    }
});

const setRating = (star: number) => {
    if (!props.readonly) {
        emit('update:modelValue', star);
    }
};
</script>

<template>
    <div class="inline-flex items-center gap-1.5 select-none">
        <div class="flex items-center gap-0.5">
            <button
                v-for="star in 5"
                :key="star"
                type="button"
                :disabled="readonly"
                @click="setRating(star)"
                :class="[
                    'transition-colors p-0.5',
                    readonly ? 'cursor-default' : 'cursor-pointer hover:scale-110 active:scale-95',
                ]"
            >
                <Star
                    :class="[
                        sizeClasses,
                        star <= Math.round(modelValue)
                            ? 'fill-amber-400 text-amber-400'
                            : 'text-slate-300 dark:text-slate-700',
                    ]"
                />
            </button>
        </div>

        <span v-if="showScore" class="text-xs font-bold text-slate-700 dark:text-slate-300 ml-1">
            {{ Number(modelValue).toFixed(1) }}
        </span>

        <span v-if="count !== undefined" class="text-xs text-slate-400 dark:text-slate-500">
            ({{ count }})
        </span>
    </div>
</template>
