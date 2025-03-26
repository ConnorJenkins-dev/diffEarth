<script setup lang="ts">
import { ref, computed } from "vue";
import LoginComponent from "./LoginComponent.vue";
import { translations } from "../languages/translations.ts";

const showLogin = ref(false);

const userRole = ref(localStorage.getItem("role") || "user");

const isAdmin = computed(() => userRole.value === "admin");

const isLoggedIn = ref(!!localStorage.getItem("token"));

const loginComponent = ref(null);

const handleLogout = () => {
    if (loginComponent.value) {
        loginComponent.value.logout();
    }
};

const updateLoginStatus = () => {
    isLoggedIn.value = !!localStorage.getItem("token");
    userRole.value = localStorage.getItem("role") || "user";
};

const getCookie = (name: string): string | null => {
    const match = document.cookie.match(new RegExp(`(^| )${name}=([^;]+)`));
    return match ? match[2] : null;
};

const savedLanguage = getCookie("language");
const currentLanguage = ref(savedLanguage || "en");
const toggleLanguage = () => {
    currentLanguage.value = currentLanguage.value === "en" ? "cy" : "en";
    document.cookie = `language=${currentLanguage.value}; path=/; max-age=31536000`;
    location.reload();
};
</script>

<template>
    <header
        class="relative w-full h-24 bg-[var(--richBlue)] text-[var(--greyBlue)] flex items-center px-6 shadow-lg rounded-lg"
    >
        <div
            class="absolute top-2 left-4 text-4xl font-bold text-[var(--greyBlue)]"
        >
            <div>
                <router-link to="/home">Team CHIL</router-link>
            </div>
            <span
                v-if="isLoggedIn"
                class="absolute -bottom-5 left-1/2 transform -translate-x-1/2 text-sm text-gray-400"
            >
                {{ userRole }}
            </span>
        </div>
        <div class="absolute bottom-2 right-4 flex gap-4">
            <router-link v-if="isAdmin" to="/dashboard">
                <div class="text-xl text-greyBlue underline">Dashboard</div>
            </router-link>
            <router-link to="/map">
                <div
                    class="text-xl text-greyBlue underline"
                    :aria-label="translations[currentLanguage].map"
                >
                    {{ translations[currentLanguage].map }}
                </div>
            </router-link>
            <router-link to="/about">
                <div
                    class="text-xl text-greyBlue underline"
                    :aria-label="translations[currentLanguage].aboutUs"
                >
                    {{ translations[currentLanguage].aboutUs }}
                </div>
            </router-link>
            <router-link to="/community">
                <div
                    class="text-xl text-[var(--greyBlue)] underline"
                    :aria-label="translations[currentLanguage].community"
                >
                    {{ translations[currentLanguage].community }}
                </div>
            </router-link>
            <router-link to="/Resources">
                <div
                    class="text-xl text-[var(--greyBlue)] underline"
                    :aria-label="translations[currentLanguage].resources"
                >
                    {{ translations[currentLanguage].resources }}
                </div>
            </router-link>
            <router-link to="/login">
                <div
                    class="text-xl text-[var(--greyBlue)] underline"
                    :aria-label="translations[currentLanguage].contactUs"
                >
                    {{ translations[currentLanguage].contactUs }}
                </div>
            </router-link>
            <router-link to="/Pricing">
                <div
                    class="text-xl text-[var(--greyBlue)] underline"
                    :aria-label="translations[currentLanguage].pricing"
                >
                    {{ translations[currentLanguage].pricing }}
                </div>
            </router-link>
            <div class="relative">
                <button
                    v-if="isLoggedIn"
                    @click="handleLogout"
                    class="bg-red-500 text-white p-2 rounded"
                >
                    Log out
                </button>
                <button
                    v-else
                    @click="showLogin = true"
                    class="bg-blue-500 text-white p-2 rounded"
                >
                    Log in
                </button>
            </div>

            <LoginComponent
                ref="loginComponent"
                :showModal="showLogin"
                @close="showLogin = false"
                @logout="updateLoginStatus"
            />
        </div>

        <!-- Language Toggle Button -->
        <button
            @click="toggleLanguage"
            class="absolute top-2 right-4 bg-gray-300 px-4 py-2 rounded text-[var(--richBlue)]"
        >
            Switch to {{ currentLanguage === "en" ? "Cymraeg" : "English" }}
        </button>
    </header>
</template>

<style scoped></style>
