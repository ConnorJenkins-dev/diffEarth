<script setup lang="ts">
import Toast from "./components/Toast.vue";
import { ref, provide } from "vue";
import { translations } from "./languages/translations.ts";
const getCookie = (name: string): string | null => {
    const match = document.cookie.match(new RegExp(`(^| )${name}=([^;]+)`));
    return match ? match[2] : null;
};

// Initialize language from cookie
const savedLanguage = getCookie("language");
const currentLanguage = ref(savedLanguage || "en");
// Function to toggle language
const toggleLanguage = () => {
    currentLanguage.value = currentLanguage.value === "en" ? "cy" : "en";
    document.cookie = `language=${currentLanguage.value}; path=/; max-age=31536000`; // Store for 1 year
};

// Provide reactive language state to all components
provide("currentLanguage", currentLanguage);
provide("translations", translations);
provide("toggleLanguage", toggleLanguage);

defineOptions({
    name: "App",
});
</script>

<template>
    <Toast />

    <div>
        <router-view />
    </div>
</template>
