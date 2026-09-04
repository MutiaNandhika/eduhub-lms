<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

interface Props {
    align?: 'left' | 'right';
    width?: '48' | '56' | '64' | '80' | '96';
    contentClasses?: string;
}

const props = withDefaults(defineProps<Props>(), {
    align: 'right',
    width: '48',
    contentClasses: 'py-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800',
});

const open = ref(false);
const dropdownRef = ref<HTMLElement | null>(null);

const closeOnEscape = (e: KeyboardEvent) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

const handleClickOutside = (e: MouseEvent) => {
    if (dropdownRef.value && !dropdownRef.value.contains(e.target as Node)) {
        open.value = false;
    }
};

onMounted(() => {
    document.addEventListener('keydown', closeOnEscape);
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div class="relative" ref="dropdownRef">
        <div @click="open = !open">
            <slot name="trigger" :open="open" />
        </div>

        <Transition
            enter-active-class="transition ease-out duration-150"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-100"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div
                v-show="open"
                :class="[
                    'absolute z-50 mt-2 rounded-xl shadow-xl',
                    align === 'right' ? 'right-0 origin-top-right' : 'left-0 origin-top-left',
                    {
                        'w-48': width === '48',
                        'w-56': width === '56',
                        'w-64': width === '64',
                        'w-80': width === '80',
                        'w-96': width === '96',
                    },
                ]"
                @click="open = false"
            >
                <div :class="['rounded-xl ring-1 ring-black/5 dark:ring-white/10 overflow-hidden', contentClasses]">
                    <slot name="content" />
                </div>
            </div>
        </Transition>
    </div>
</template>
