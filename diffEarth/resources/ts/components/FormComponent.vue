<script setup lang="ts">
import { defineProps, defineEmits, reactive, ref } from "vue";

const props = defineProps<{
    fields: { name: string; type: string; label: string; value?: any }[];
    submitLabel?: string;
    apiEndpoint: string; // Unique API endpoint
    method?: "POST" | "PUT"; // Default to POST if not specified
}>();

const emit = defineEmits(["submit", "success", "error"]);

const formData = reactive(
    props.fields.reduce(
        (acc, field) => {
            acc[field.name] = field.value || "";
            return acc;
        },
        {} as Record<string, any>,
    ),
);

const loading = ref(false);
const errorMessage = ref("");

const handleSubmit = async () => {
    loading.value = true;
    errorMessage.value = "";

    try {
        const response = await fetch(props.apiEndpoint, {
            method: props.method || "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData),
        });

        if (!response.ok) {
            throw new Error("Failed to submit data");
        }

        const responseData = await response.json();
        emit("success", responseData);
    } catch (error: any) {
        errorMessage.value = error.message;
        emit("error", error.message);
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <form
        @submit.prevent="handleSubmit"
        class="space-y-4 p-4 bg-white shadow rounded"
    >
        <div v-for="field in fields" :key="field.name">
            <label :for="field.name" class="block font-semibold mb-1">{{
                field.label
            }}</label>
            <input
                v-if="field.type !== 'textarea'"
                :type="field.type"
                v-model="formData[field.name]"
                :id="field.name"
                class="w-full p-2 border rounded"
            />
            <textarea
                v-else
                v-model="formData[field.name]"
                :id="field.name"
                class="w-full p-2 border rounded"
            ></textarea>
        </div>
        <button
            type="submit"
            class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600"
            :disabled="loading"
        >
            {{ loading ? "Submitting..." : submitLabel || "Submit" }}
        </button>
        <p v-if="errorMessage" class="text-red-500 mt-2">{{ errorMessage }}</p>
    </form>
</template>
