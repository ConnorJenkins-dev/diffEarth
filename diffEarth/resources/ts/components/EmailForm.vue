<script setup>
import { ref } from "vue";
import { useTranslation } from "../composables/useTranslation";

const { t } = useTranslation();
const form = ref({
    location: "",
    column: "",
    threshold: "",
    emails: "",
});

const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const validateEmails = (emailString) => {
    if (!emailString) return t.empty;

    // Detect multiple @ symbols in the entire input without commas for lists of emails
    if (
        emailString.includes("@@") ||
        (emailString.match(/@/g) || []).length > emailString.split(",").length
    ) {
        return t.commaSeparated;
    }

    const emails = emailString.split(",").map((email) => email.trim());

    for (let email of emails) {
        if (!emailRegex.test(email)) {
            return `${t.value.invalid} ${email} ${t.value.pleaseEnter}`;
        }
    }

    return null; // No errors
};

const sendEmail = async () => {
    const errorMessage = validateEmails(form.value.emails);
    if (errorMessage) {
        alert(errorMessage);
        return;
    }

    try {
        const response = await fetch(
            `${import.meta.env.BASE_URL}api/send-email`,
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    location: form.value.location,
                    column: form.value.column,
                    threshold: form.value.threshold,
                    emails: form.value.emails
                        .split(",")
                        .map((email) => email.trim()),
                }),
            },
        );

        const data = await response.json(); // Parse JSON response

        if (!response.ok) {
            throw new Error(data.message || t.sendFailure);
        }

        alert(data.message); // Show success message
    } catch (error) {
        console.error("Error sending email:", error);
        alert(error.message || "Failed to send email");
    }
};
</script>
<template>
    <div class="p-6 bg-white rounded-lg w-full max-w-lg">
        <h2 class="text-lg font-semibold mb-4">{{ t.title }}</h2>
        <form @submit.prevent="sendEmail">
            <div class="mb-3">
                <label class="block font-medium">{{ t.locationLabel }}</label>
                <input
                    type="text"
                    v-model="form.location"
                    class="w-full border rounded p-2"
                    required
                />
            </div>

            <div class="mb-3">
                <label class="block font-medium">{{ t.columnLabel }}</label>
                <input
                    type="text"
                    v-model="form.column"
                    class="w-full border rounded p-2"
                    required
                />
            </div>

            <div class="mb-3">
                <label class="block font-medium">{{ t.thresholdLabel }}</label>
                <input
                    type="number"
                    v-model="form.threshold"
                    class="w-full border rounded p-2"
                    required
                />
            </div>

            <div class="mb-3">
                <label class="block font-medium">{{ t.emailsLabel }}</label>
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
                {{ t.submitButton }}
            </button>
        </form>
    </div>
</template>

<style scoped>
input {
    border: 1px solid #ccc;
}
</style>
