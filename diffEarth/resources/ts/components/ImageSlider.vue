<script setup lang="ts">
import { ref } from "vue";

const props = defineProps<{ images: string[] }>();
const currentIndex = ref(0);

const nextImage = () => {
    currentIndex.value = (currentIndex.value + 1) % props.images.length;
};

const prevImage = () => {
    currentIndex.value =
        (currentIndex.value - 1 + props.images.length) % props.images.length;
};
</script>

<template>
    <div class="relative w-full overflow-hidden rounded-lg shadow-lg">
        <img
            :src="images[currentIndex]"
            class="w-full object-cover transition-opacity duration-300"
        />

        <!-- Navigation Buttons -->
        <button
            @click="prevImage"
            class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-transparent text-white p-4 text-3xl rounded-full shadow-md hover:bg-gray-200"
        >
            ◀
        </button>
        <button
            @click="nextImage"
            class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-transparent text-white p-4 text-3xl rounded-full shadow-md hover:bg-gray-200"
        >
            ▶
        </button>
    </div>
</template>
