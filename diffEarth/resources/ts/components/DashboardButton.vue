<script setup lang="ts">
// Implemented slots as an alternative to props: https://vuejs.org/guide/components/slots.html
</script>

<template>
    <button
        class="border-2 rounded-full w-full bg-[var(--darkBlueAlt)] hover:bg-[var(--lightBlue)] text-[var(--greyBlueAlt)] hover:text-[var(--darkBlueAlt)] py-0.5 px-4 transition border-[var(--greyBlue)]"
    >
        <slot></slot>
    </button>
</template>

<style scoped></style>
