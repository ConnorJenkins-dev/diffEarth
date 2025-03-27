<script setup lang="ts">
defineProps({
    imgMain: {
        type: String,
        default: "https://placehold.co/600x400",
    },
    heading: {
        type: String,
        default: "Heading",
    },
    content: {
        type: String,
        default: "About text...",
    },
    imgProfile: {
        type: String,
        default: "https://placehold.co/600x400",
    },
    name: {
        type: String,
        default: "Name",
    },
    date: {
        type: String,
        default: "Date",
    },
});
</script>

<template>
    <div
        class="flex flex-col border-1 border-gray-200 rounded w-150 m-3 shadow-md"
    >
        <img
            v-if="imgMain"
            class="rounded-tr-md rounded-tl-md max-h-100"
            :src="imgMain"
            alt="main"
        />
        <section class="m-3">
            <h1 class="font-bold text-2xl">{{ heading }}</h1>
        </section>
        <article class="m-3">
            <p class="text-gray-800">{{ content }}</p>
        </article>
        <aside class="flex flex-row m-3 items-center">
            <img
                class="rounded-full w-20 h-20 m-3"
                :src="imgProfile"
                alt="profile"
            />
            <div>
                <h2 class="font-bold m-1">{{ name }}</h2>
                <p class="m-1 text-gray-600">{{ date }}</p>
            </div>
        </aside>
    </div>
</template>

<style scoped></style>
