<script setup lang="ts">
import { useToast } from "../composables/useToast";
const { toasts } = useToast();
</script>

<template>
    <div
        class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 pointer-events-none"
    >
        <transition-group name="fade">
            <div
                v-for="toast in toasts"
                :key="toast.id"
                :class="[
                    'px-4 py-2 mb-2 rounded shadow',
                    toast.type === 'success' ? 'bg-green-500' : 'bg-red-500',
                ]"
            >
                <p class="text-white">{{ toast.message }}</p>
            </div>
        </transition-group>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
