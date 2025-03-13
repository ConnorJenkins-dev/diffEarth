<script setup lang="ts">
import Toast from "./components/Toast.vue";
defineOptions({
    name: "App",
});
</script>

<template>
    <Toast />
    <div>
        <router-view></router-view>
    </div>
</template>
