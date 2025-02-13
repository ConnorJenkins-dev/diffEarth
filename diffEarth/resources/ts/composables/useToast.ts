import { ref } from "vue";

export type ToastType = "success" | "error";

export interface Toast {
    id: number;
    message: string;
    type: ToastType;
}

const toasts = ref<Toast[]>([]);

export function useToast() {
    const showToast = (
        message: string,
        type: ToastType = "success",
        duration: number = 15000,
    ): void => {
        const id = Date.now();
        toasts.value.push({ id, message, type });
        setTimeout(() => {
            remove(id);
        }, duration);
    };

    const remove = (id: number): void => {
        toasts.value = toasts.value.filter((t) => t.id !== id);
    };

    return { toasts, showToast, remove };
}
