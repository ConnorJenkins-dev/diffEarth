<template>
    <div
        v-if="showModal"
        class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
    >
        <div class="bg-white p-6 rounded-lg shadow-lg w-96 relative z-50">
            <h2 class="text-2xl font-semibold mb-4 text-stone-700">
                {{ t.createAccount }}
            </h2>

            <!-- Signup Form -->
            <form @submit.prevent="register">
                <input
                    type="text"
                    v-model="name"
                    :placeholder="t.fullName"
                    required
                    class="w-full p-2 mb-2 border rounded text-stone-700"
                />
                <input
                    type="email"
                    v-model="email"
                    :placeholder="t.email"
                    required
                    class="w-full p-2 mb-2 border rounded text-stone-700"
                />
                <input
                    type="password"
                    v-model="password"
                    :placeholder="t.password"
                    required
                    class="w-full p-2 mb-2 border rounded text-stone-700"
                />
                <input
                    type="password"
                    v-model="confirmPassword"
                    :placeholder="t.confirmPassword"
                    required
                    class="w-full p-2 mb-2 border rounded text-stone-700"
                />

                <button
                    type="submit"
                    class="w-full bg-green-500 text-white p-2 rounded hover:bg-green-600"
                >
                    {{ t.signUp }}
                </button>
            </form>

            <!-- Error Message -->
            <p v-if="errorMessage" class="text-red-500 mt-2">
                {{ errorMessage }}
            </p>

            <!-- Close Button -->
            <button
                @click="closeModal"
                class="mt-4 w-full bg-gray-300 p-2 rounded hover:bg-gray-400 text-stone-700"
            >
                {{ t.close }}
            </button>
        </div>
        <!-- Toast Notification -->
        <div
            v-if="showToast"
            class="fixed bottom-5 left-1/2 transform -translate-x-1/2 bg-red-500 text-white p-3 rounded-lg shadow-lg w-96"
            role="alert"
        >
            <span>{{ toastMessage }}</span>
        </div>
    </div>
</template>

<script setup>
import { ref, defineProps, defineEmits } from "vue";
import { useTranslation } from "../composables/useTranslation";

const { t } = useTranslation();
const props = defineProps({ showModal: Boolean });
const emit = defineEmits(["close", "registered"]);

const name = ref("");
const email = ref("");
const password = ref("");
const confirmPassword = ref("");
const errorMessage = ref("");
const showToast = ref(false);
const toastMessage = ref("");
// Email validation regex (ensures @ and at least one . after it)
const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

async function checkIfEmailExists(email) {
    try {
        const response = await fetch("/api/emails");
        if (!response.ok) throw new Error("Failed to fetch emails.");

        const existingEmails = await response.json();
        return existingEmails.includes(email);
    } catch (error) {
        console.error("Error checking email:", error);
        return false;
    }
}

function handleRegisterSuccess(response) {
    const { token, role } = response;

    // Store the token and role in localStorage
    localStorage.setItem("token", token);
    localStorage.setItem("role", role);

    // Optionally, redirect the user after registration
    window.location.href = "/dashboard";
}

const register = async () => {
    if (!emailPattern.test(email.value)) {
        toastMessage.value = t.invalidEmail;
        showToast.value = true;
        setTimeout(() => {
            showToast.value = false;
        }, 3000);
        return;
    }

    const emailExists = await checkIfEmailExists(email.value);

    if (emailExists) {
        console.error("Email already in use");
        toastMessage.value = t.emailInUse;
        showToast.value = true;
        setTimeout(() => {
            showToast.value = false;
        }, 3000);
        return;
    }

    // Check if password and confirm password match and are at least 6 characters long
    if (password.value.length < 6 || confirmPassword.value.length < 6) {
        toastMessage.value = t.passwordShort;
        showToast.value = true;
        setTimeout(() => {
            showToast.value = false;
        }, 3000); // Toast will disappear after 3 seconds
        return;
    }

    if (password.value !== confirmPassword.value) {
        toastMessage.value = t.passwordMismatch;
        showToast.value = true;
        setTimeout(() => {
            showToast.value = false;
        }, 3000); // Toast will disappear after 3 seconds
        return;
    }

    try {
        const response = await fetch("/api/register", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                name: name.value,
                email: email.value,
                password: password.value,
                password_confirmation: confirmPassword.value,
            }),
        });

        if (response.ok) {
            const data = await response.json();
            console.log(data);
            handleRegisterSuccess(data);
        } else {
            const errorData = await response.json(); // Read error body if response is not ok
            console.log(errorData);
            throw new Error(errorData.message || t.registrationFailed);
        }
    } catch (error) {
        console.error("Error:", error.message);
        toastMessage.value = error.message;
        showToast.value = true;
        setTimeout(() => {
            showToast.value = false;
        }, 3000); // Toast will disappear after 3 seconds
    }
};

const closeModal = () => {
    emit("close");
};
</script>

<style>
/* Ensure the modal is always on top */
.z-50 {
    z-index: 50;
}
</style>
