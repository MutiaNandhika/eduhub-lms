<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

interface Props {
    size?: 'sm' | 'md' | 'lg' | 'xl';
    showTagline?: boolean;
    href?: string;
    variant?: 'auto' | 'dark' | 'light';
}

const props = withDefaults(defineProps<Props>(), {
    size: 'md',
    showTagline: true,
    href: '/',
    variant: 'auto',
});

const iconSizeClasses = computed(() => {
    switch (props.size) {
        case 'sm':
            return 'w-7 h-7';
        case 'md':
            return 'w-9 h-9';
        case 'lg':
            return 'w-11 h-11';
        case 'xl':
            return 'w-14 h-14';
        default:
            return 'w-9 h-9';
    }
});

const textSizeClasses = computed(() => {
    switch (props.size) {
        case 'sm':
            return { title: 'text-base font-extrabold', subtitle: 'text-[8px] tracking-[0.2em]' };
        case 'md':
            return { title: 'text-xl font-black', subtitle: 'text-[9px] tracking-[0.22em]' };
        case 'lg':
            return { title: 'text-2xl font-black', subtitle: 'text-[10px] tracking-[0.24em]' };
        case 'xl':
            return { title: 'text-3xl font-black', subtitle: 'text-[11px] tracking-[0.26em]' };
        default:
            return { title: 'text-xl font-black', subtitle: 'text-[9px] tracking-[0.22em]' };
    }
});
</script>

<template>
    <component
        :is="href ? Link : 'div'"
        :href="href || undefined"
        class="inline-flex items-center gap-3 select-none group focus:outline-none"
    >
        <!-- Custom Graduation Cap Icon (from provided logo) -->
        <div
            :class="[
                'relative flex items-center justify-center shrink-0 transition-transform duration-200 group-hover:scale-105',
                iconSizeClasses,
            ]"
        >
            <svg
                viewBox="0 0 100 80"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                class="w-full h-full drop-shadow-sm"
            >
                <!-- Cap Top Diamond (Mortarboard) -->
                <polygon
                    points="50,12 94,36 50,60 6,36"
                    class="fill-slate-900 dark:fill-white transition-colors"
                />
                <!-- Cap Skullcap Lower Base -->
                <path
                    d="M26,45.5 L26,62 C26,71 74,71 74,62 L74,45.5 C68,49.5 59,51.5 50,51.5 C41,51.5 32,49.5 26,45.5 Z"
                    class="fill-slate-900 dark:fill-white transition-colors"
                />
                <!-- Tassel Ribbon & Bobble -->
                <path
                    d="M74,40 Q84,46 82,57"
                    class="stroke-slate-900 dark:stroke-white stroke-[3.5] stroke-linecap-round"
                    fill="none"
                />
                <circle
                    cx="81"
                    cy="60"
                    r="4"
                    class="fill-slate-900 dark:fill-white"
                />
            </svg>
        </div>

        <!-- Typography: eduhub + LEARN.PRACTICE.GROW. -->
        <div class="flex flex-col leading-tight text-left">
            <span
                :class="[
                    'tracking-tight font-sans transition-colors',
                    variant === 'light'
                        ? 'text-white'
                        : variant === 'dark'
                        ? 'text-slate-900'
                        : 'text-slate-900 dark:text-white',
                    textSizeClasses.title,
                ]"
            >
                eduhub
            </span>
            <span
                v-if="showTagline"
                :class="[
                    'uppercase font-bold transition-colors -mt-0.5',
                    variant === 'light'
                        ? 'text-slate-300'
                        : variant === 'dark'
                        ? 'text-slate-400'
                        : 'text-slate-400 dark:text-slate-400',
                    textSizeClasses.subtitle,
                ]"
            >
                LEARN. PRACTICE. GROW.
            </span>
        </div>
    </component>
</template>
