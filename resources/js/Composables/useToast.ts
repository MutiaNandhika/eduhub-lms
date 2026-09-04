import { ref } from 'vue';

export interface ToastMessage {
    id: number;
    type: 'success' | 'error' | 'warning' | 'info';
    title?: string;
    message: string;
    duration?: number;
}

const toasts = ref<ToastMessage[]>([]);
let toastIdCounter = 0;

export function useToast() {
    const addToast = (toast: Omit<ToastMessage, 'id'>) => {
        const id = ++toastIdCounter;
        const newToast: ToastMessage = {
            id,
            duration: 4000,
            ...toast,
        };

        toasts.value.push(newToast);

        if (newToast.duration && newToast.duration > 0) {
            setTimeout(() => {
                removeToast(id);
            }, newToast.duration);
        }
    };

    const removeToast = (id: number) => {
        toasts.value = toasts.value.filter((t) => t.id !== id);
    };

    const success = (message: string, title: string = 'Success') => {
        addToast({ type: 'success', title, message });
    };

    const error = (message: string, title: string = 'Error') => {
        addToast({ type: 'error', title, message, duration: 6000 });
    };

    const info = (message: string, title: string = 'Information') => {
        addToast({ type: 'info', title, message });
    };

    const warning = (message: string, title: string = 'Notice') => {
        addToast({ type: 'warning', title, message });
    };

    return {
        toasts,
        addToast,
        removeToast,
        success,
        error,
        info,
        warning,
    };
}
