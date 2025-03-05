<script setup>
import GlobeComponent from "./GlobeComponent.vue";
import Header from "./Header.vue";
</script>

<template>
    <Header />
    <body class="flex flex-row w-full">
        <section class="m-1 items-center justify-center">
            <section id="globe" class="grid items-center justify-center">
                <GlobeComponent />
            </section>
        </section>
    </body>
</template>

<style scoped></style>
