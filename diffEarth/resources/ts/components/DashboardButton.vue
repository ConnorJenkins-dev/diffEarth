<script setup lang="ts">
// Implemented slots as an alternative to props: https://vuejs.org/guide/components/slots.html
</script>

<template>
    <button
        class="border-2 rounded-full w-full bg-blue-500 hover:bg-blue-700 text-white py-0.5 px-4 shadow-lg"
    >
        <slot></slot>
    </button>
</template>

<style scoped></style>
