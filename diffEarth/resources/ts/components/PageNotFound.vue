<script setup lang="ts">
import DashboardButton from "./DashboardButton.vue";
</script>

<template>
    <h1>404</h1>
    <h2>The page you attempted to go to does not exist</h2>
    <router-link to="/home">
        <DashboardButton class="max-w-fit"> Return to home </DashboardButton>
    </router-link>
</template>

<style scoped></style>
