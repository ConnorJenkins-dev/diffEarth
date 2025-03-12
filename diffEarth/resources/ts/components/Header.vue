<script setup lang="ts"></script>

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
        </div>
        <div class="absolute bottom-2 right-4 flex gap-4">
            <router-link to="/about">
                <div class="text-xl text-greyBlue underline">About Us</div>
            </router-link>
            <router-link to="/community">
                <div class="text-xl text-[var(--greyBlue)] underline">
                    Community
                </div>
            </router-link>
            <router-link to="/Resources">
                <div class="text-xl text-[var(--greyBlue)] underline">
                    Resources
                </div>
            </router-link>
            <router-link to="/login">
                <div class="text-xl text-[var(--greyBlue)] underline">
                    Contact Us
                </div>
            </router-link>
            <router-link to="/Pricing">
                <div class="text-xl text-[var(--greyBlue)] underline">
                    Pricing
                </div>
            </router-link>
            <router-link to="/login">
                <div class="text-xl text-[var(--greyBlue)] underline">
                    Log In
                </div>
            </router-link>
        </div>
    </header>
</template>

<style scoped></style>
