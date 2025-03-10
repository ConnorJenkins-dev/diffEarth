<script setup lang="ts">
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import { useToast } from "../composables/useToast";
import DashboardButton from "./DashboardButton.vue";

const { showToast } = useToast();
const route = useRoute();
const biographyText = ref("");
const previousBiographyText = ref("");
const isEditing = ref(false);
const isLoading = ref(true);
const loadingText = "Loading biography...";
const collaboratorId = route.params.id;

const getBiographyText = async () => {
    isLoading.value = true;
    try {
        const response = await fetch(`/api/biographyText/${collaboratorId}`);
        if (!response.ok) {
            console.error("Failed to fetch biography text");
            return;
        }
        const data = await response.json();
        biographyText.value = data.biography;
        isLoading.value = false;
        previousBiographyText.value = data.biography;
    } catch (error) {
        console.error(error);
    }
    return;
};

const saveBiography = async () => {
    try {
        const response = await fetch(`/api/biographyText/${collaboratorId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ biography: biographyText.value }),
        });
        if (!response.ok) {
            console.error("Failed to save biography");
            showToast("Failed to save biography!", "error");
            return;
        }
        isEditing.value = false;
    } catch (error) {
        console.error(error);
    }
};

const toggleEdit = async () => {
    if (isEditing.value == true) {
        isEditing.value = false;
        biographyText.value = previousBiographyText.value;
        return;
    }
    isEditing.value = true;
};

onMounted(async () => {
    await getBiographyText();
});
</script>

<template>
    <div class="flex flex-col justify-center rounded-sm mt-8.5 px-1">
        <img
            id="image"
            src="https://placehold.co/600x400"
            class="my-3 shadow rounded-sm"
        />
        <div class="flex flex-row items-baseline">
            <h1 class="text-2xl font-bold my-3">Biography</h1>
            <h1
                class="text-sm ml-3 text-blue-500 cursor-pointer"
                @click="toggleEdit"
            >
                {{ isEditing ? "Cancel" : "Edit" }} <i class="pi pi-pencil"></i>
            </h1>
        </div>
        <div v-if="isEditing">
            <textarea
                v-model="biographyText"
                class="w-full p-2 border rounded-md"
            ></textarea>
            <DashboardButton
                class="max-w-fit cursor-pointer"
                @click="saveBiography"
                >Save</DashboardButton
            >
        </div>
        <p v-else>{{ isLoading ? loadingText : biographyText }}</p>
    </div>
</template>

<style scoped></style>
