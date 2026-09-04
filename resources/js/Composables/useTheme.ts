import { ref, onMounted } from 'vue';

const isDark = ref<boolean>(false);

export function useTheme() {
    const initTheme = () => {
        if (typeof window === 'undefined') return;
        const storedTheme = localStorage.getItem('eduhub_theme');
        if (storedTheme === 'dark' || (!storedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            isDark.value = true;
            document.documentElement.classList.add('dark');
        } else {
            isDark.value = false;
            document.documentElement.classList.remove('dark');
        }
    };

    const toggleTheme = () => {
        isDark.value = !isDark.value;
        if (isDark.value) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('eduhub_theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('eduhub_theme', 'light');
        }
    };

    onMounted(() => {
        initTheme();
    });

    return {
        isDark,
        toggleTheme,
        initTheme,
    };
}
