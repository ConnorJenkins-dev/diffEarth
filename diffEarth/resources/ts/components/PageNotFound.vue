<script setup lang="ts">
import DashboardButton from "./DashboardButton.vue";
import { useTranslation } from "../composables/useTranslation";
const { t } = useTranslation();
</script>

<template>
    <h1>{{ t.pageNotFound }}</h1>
    <h2>{{ t.pageNotFoundMessage }}</h2>
    <router-link to="/home">
        <DashboardButton class="max-w-fit">{{ t.returnHome }}</DashboardButton>
    </router-link>
</template>

<style scoped></style>
