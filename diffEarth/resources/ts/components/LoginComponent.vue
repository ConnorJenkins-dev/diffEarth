<template>
    <div
        v-if="showModal"
        class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
    >
        <div class="bg-white p-6 rounded-lg shadow-lg w-96 relative z-50">
            <h2 class="text-2xl font-semibold mb-4 text-stone-700">
                {{ isLoggedIn ? t.logout : t.login }}
            </h2>

            <!-- Login Form -->
            <form v-if="!isLoggedIn" @submit.prevent="login">
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

                <button
                    type="submit"
                    class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600"
                >
                    {{ t.login }}
                </button>
            </form>

            <!-- Logout Message -->
            <div v-else>
                <p class="text-gray-700 mb-4">
                    {{ t.YouAreloggedInAs }} <strong>{{ userRole }}</strong
                    >.
                </p>
                <button
                    @click="logout"
                    class="w-full bg-red-500 text-white p-2 rounded hover:bg-red-600"
                >
                    {{ t.logout }}
                </button>
            </div>

            <!-- Sign Up Button -->
            <p class="text-center mt-4 text-gray-600 text-sm">
                {{ t.dontHaveAccount }}
                <button
                    @click="openSignup"
                    class="text-blue-500 hover:underline"
                >
                    {{ t.signUp }}
                </button>
            </p>

            <!-- Close Button -->
            <button
                @click="closeModal"
                class="mt-4 w-full bg-gray-300 p-2 rounded hover:bg-gray-400 text-stone-700"
            >
                {{ t.close }}
            </button>

            <p v-if="errorMessage" class="text-red-500 mt-2">
                {{ errorMessage }}
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, defineProps, defineEmits } from "vue";

import { useTranslation } from "../composables/useTranslation";

const { t } = useTranslation();
const props = defineProps({ showModal: Boolean });
const emit = defineEmits(["close", "logout", "openSignup"]);

const email = ref("");
const password = ref("");
const errorMessage = ref("");

const isLoggedIn = computed(() => !!localStorage.getItem("token"));
const userRole = computed(() => localStorage.getItem("role") || "User");

const login = async () => {
    try {
        console.log("Attempting login...");

        const response = await fetch(`${import.meta.env.BASE_URL}api/login`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                email: email.value,
                password: password.value,
            }),
        });

        if (!response.ok) {
            throw new Error("Invalid email or password.");
        }

        const data = await response.json();
        console.log("Login successful:", data);

        localStorage.setItem("token", data.token);
        localStorage.setItem("role", data.roles);

        window.location.reload();
    } catch (error) {
        console.error("Login failed:", error.message);
        errorMessage.value = error.message;
    }
};

const logout = async () => {
    try {
        console.log("Logging out...");

        const response = await fetch(`${import.meta.env.BASE_URL}api/logout`, {
            method: "POST",
            headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`,
                "Content-Type": "application/json",
            },
        });

        if (!response.ok) {
            throw new Error("Logout failed.");
        }

        console.log("Logout successful");

        localStorage.removeItem("token");
        localStorage.removeItem("role");

        emit("logout");

        window.location.reload();
    } catch (error) {
        console.error("Error logging out:", error.message);
    }
};

defineExpose({ logout });

const openSignup = () => {
    emit("openSignup"); // Tell parent component to open signup modal
};

const closeModal = () => {
    emit("close");
};
</script>
