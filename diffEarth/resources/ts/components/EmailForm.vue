<script setup>
import { ref, onMounted } from "vue";

const form = ref({
    location: "",
    column: "",
    threshold: "",
    emails: "",
});

const columns = ref([]); // Store all columns

// Fetch all columns on component mount
const fetchColumns = async () => {
    try {
        const response = await fetch("/api/columns"); // Adjust the API endpoint if needed
        if (!response.ok) throw new Error("Failed to fetch columns");
        columns.value = await response.json();
    } catch (error) {
        console.error(error);
    }
};

// Fetch columns when component loads
onMounted(fetchColumns);

const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const validateEmails = (emailString) => {
    if (!emailString) return "Please enter at least one email.";

    if (
        emailString.includes("@@") ||
        (emailString.match(/@/g) || []).length > emailString.split(",").length
    ) {
        return "Emails must be comma-separated. Please separate multiple emails with commas.";
    }

    const emails = emailString.split(",").map((email) => email.trim());
    for (let email of emails) {
        if (!emailRegex.test(email)) {
            return `Invalid email: "${email}". Please enter a valid email.`;
        }
    }
    return null;
};

const sendEmail = async () => {
    const errorMessage = validateEmails(form.value.emails);
    if (errorMessage) {
        alert(errorMessage);
        return;
    }

    try {
        const response = await fetch("/api/send-email", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                location: form.value.location,
                column: form.value.column,
                threshold: form.value.threshold,
                emails: form.value.emails
                    .split(",")
                    .map((email) => email.trim()),
            }),
        });

        const data = await response.json();
        if (!response.ok)
            throw new Error(data.message || "Failed to send email");
        alert(data.message);
    } catch (error) {
        console.error("Error sending email:", error);
        alert(error.message || "Failed to send email");
    }
};
</script>

<template>
    <div class="p-6 bg-white rounded-lg w-full max-w-lg">
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
                <label class="block font-medium">Select Column</label>
                <select
                    v-model="form.column"
                    class="w-full border rounded p-2"
                    required
                >
                    <option value="" disabled>Select a column</option>
                    <option
                        v-for="column in columns"
                        :key="column.id"
                        :value="column.name"
                    >
                        {{ column.name }}
                    </option>
                </select>
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
                class="bg-[var(--lightBlue)] border-2 border-[var(--darkestBlue)] rounded-lg px-4 py-2 font-bold transition hover:bg-[var(--darkestBlue)] hover:border-[var(--lightBlue)] hover:text-white"
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
