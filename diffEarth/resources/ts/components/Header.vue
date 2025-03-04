<script setup lang="ts">
import DashboardButton from "./DashboardButton.vue";
</script>

<template>
    <header
        class="flex flex-row w-full max-h-20 bg-[#3bc4f9] rounded-b-sm justify-content-center"
    >
        <div id="logo" class="w-1/2 flex justify-start">
            <router-link to="/home">
                <img
                    src="https://logoipsum.com/logoipsum.png"
                    alt="logo"
                    class="rounded-sm m-3"
                    width="100"
                />
            </router-link>
        </div>
        <div class="w-1/2">
            <nav>
                <ul class="flex flex-row justify-end m-3">
                    <li class="m-2 whitespace-nowrap">
                        <router-link to="/about">
                            <DashboardButton>
                                <h3>About Us</h3>
                            </DashboardButton>
                        </router-link>
                    </li>
                    <li class="m-2">
                        <DashboardButton>
                            <h3>Solutions</h3>
                        </DashboardButton>
                    </li>
                    <li class="m-2">
                        <DashboardButton>
                            <h3>Community</h3>
                        </DashboardButton>
                    </li>
                    <li class="m-2">
                        <DashboardButton>
                            <h3>Resources</h3>
                        </DashboardButton>
                    </li>
                    <li class="m-2">
                        <DashboardButton>
                            <h3>Pricing</h3>
                        </DashboardButton>
                    </li>
                    <li class="m-2 whitespace-nowrap">
                        <DashboardButton>
                            <h3>Contact Us</h3>
                        </DashboardButton>
                    </li>
                    <li class="my-2 mx-3 whitespace-nowrap">
                        <DashboardButton class="flex flex-row items-center">
                            <h3>Sign Up/ Login</h3>
                            <i class="pi pi-user px-1" />
                        </DashboardButton>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
</template>

<style scoped></style>
