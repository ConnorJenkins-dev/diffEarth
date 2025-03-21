<script setup>
import { reactive, provide } from "vue";

const languageState = reactive({
    currentLanguage: "en", // default language
});

// Provide the language state to child components
provide("languageState", languageState);
</script>

<template>
    <slot />
</template>
