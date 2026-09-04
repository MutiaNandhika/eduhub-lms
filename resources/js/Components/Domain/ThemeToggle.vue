<script setup lang="ts">
import { useTheme } from '@/Composables/useTheme';
import { Sun, Moon } from 'lucide-vue-next';

const { isDark, toggleTheme } = useTheme();
</script>

<template>
    <button
        type="button"
        @click="toggleTheme"
        :title="isDark ? 'Switch to Light mode' : 'Switch to Dark mode'"
        class="p-2 rounded-xl text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800/80 transition-colors cursor-pointer select-none"
    >
        <Moon v-if="!isDark" class="w-5 h-5 transition-transform hover:rotate-12" />
        <Sun v-else class="w-5 h-5 transition-transform hover:rotate-45 text-amber-400" />
    </button>
</template>
