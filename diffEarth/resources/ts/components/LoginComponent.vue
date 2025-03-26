<template>
    <div
        v-if="showModal"
        class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
    >
        <div class="bg-white p-6 rounded-lg shadow-lg w-96 relative z-50">
            <h2 class="text-2xl font-semibold mb-4">
                {{ isLoggedIn ? "Logout" : "Login" }}
            </h2>

            <!-- Login Form -->
            <form v-if="!isLoggedIn" @submit.prevent="login">
                <input
                    type="email"
                    v-model="email"
                    placeholder="Email"
                    required
                    class="w-full p-2 mb-2 border rounded"
                />
                <input
                    type="password"
                    v-model="password"
                    placeholder="Password"
                    required
                    class="w-full p-2 mb-2 border rounded"
                />

                <button
                    type="submit"
                    class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600"
                >
                    Login
                </button>
            </form>

            <!-- Logout Message -->
            <div v-else>
                <p class="text-gray-700 mb-4">
                    You are logged in as <strong>{{ userRole }}</strong
                    >.
                </p>
                <button
                    @click="logout"
                    class="w-full bg-red-500 text-white p-2 rounded hover:bg-red-600"
                >
                    Logout
                </button>
            </div>

            <!-- Close Button -->
            <button
                @click="closeModal"
                class="mt-4 w-full bg-gray-300 p-2 rounded hover:bg-gray-400"
            >
                Close
            </button>

            <p v-if="errorMessage" class="text-red-500 mt-2">
                {{ errorMessage }}
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, defineProps, defineEmits, onMounted } from "vue";
import { useRouter } from "vue-router";

const props = defineProps({ showModal: Boolean });
const emit = defineEmits(["close", "logout"]);

const email = ref("");
const password = ref("");
const errorMessage = ref("");
const router = useRouter();

// Check if user is logged in
const isLoggedIn = computed(() => !!localStorage.getItem("token"));
const userRole = computed(() => localStorage.getItem("role") || "User");
// const userName = computed(() => localStorage.getItem('name') || '');

const login = async () => {
    try {
        console.log("Attempting login...");

        const response = await fetch("/api/login", {
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

        // Store token and user role
        localStorage.setItem("token", data.token);
        localStorage.setItem("role", data.role);

        // Refresh to update modal
        window.location.reload();
    } catch (error) {
        console.error("Login failed:", error.message);
        errorMessage.value = error.message;
    }
};

const logout = async () => {
    try {
        console.log("Logging out...");

        const response = await fetch("/api/logout", {
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

        // Clear localStorage
        localStorage.removeItem("token");
        localStorage.removeItem("role");

        emit("logout");

        // Refresh the page to update UI
        window.location.reload();
    } catch (error) {
        console.error("Error logging out:", error.message);
    }
};

defineExpose({ logout });

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
