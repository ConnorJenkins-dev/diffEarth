<script setup>
import { ref } from "vue";
import axios from "axios";

const form = ref({
    location: "",
    column: "",
    threshold: "",
    emails: "",
});

const sendEmail = async () => {
    try {
        const response = await axios.post("/api/send-email", {
            location: form.value.location,
            column: form.value.column,
            threshold: form.value.threshold,
            emails: form.value.emails.split(",").map((email) => email.trim()), // Convert string to array
        });

        alert(response.data.message);
    } catch (error) {
        console.error("Error sending email:", error);
        alert("Failed to send email");
    }
};
</script>

<template>
    <div class="p-6 bg-white rounded-lg shadow-md w-full max-w-lg">
        <h2 class="text-lg font-semibold mb-4">Send Email Notification</h2>
        <form @submit.prevent="sendEmail">
            <div class="mb-3">
                <label class="block font-medium">Location Name</label>
                <input
                    type="text"
                    v-model="form.location"
                    class="w-full border rounded p-2"
                    required
                />
            </div>

            <div class="mb-3">
                <label class="block font-medium">Column</label>
                <input
                    type="text"
                    v-model="form.column"
                    class="w-full border rounded p-2"
                    required
                />
            </div>

            <div class="mb-3">
                <label class="block font-medium">Threshold</label>
                <input
                    type="number"
                    v-model="form.threshold"
                    class="w-full border rounded p-2"
                    required
                />
            </div>

            <div class="mb-3">
                <label class="block font-medium"
                    >Emails (comma-separated)</label
                >
                <input
                    type="text"
                    v-model="form.emails"
                    class="w-full border rounded p-2"
                    required
                />
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
            >
                Send Email
            </button>
        </form>
    </div>
</template>

<style scoped>
input {
    border: 1px solid #ccc;
}
</style>
