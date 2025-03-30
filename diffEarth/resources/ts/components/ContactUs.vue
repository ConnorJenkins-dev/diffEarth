<script setup lang="ts">
import Header from "./Header.vue";
import { useTranslation } from "../composables/useTranslation";
const { t } = useTranslation();
</script>

<template>
    <Header />
    <div class="flex flex-col items-center w-full bg-[var(--greyBlue)] p-4">
        <!-- Apply the same width settings as the header -->
        <section
            class="w-full max-w-[2500px] mx-auto m-1 border-2 border-[var(--darkBlue)] bg-white rounded-lg shadow-md p-6"
        >
            <div>
                <h2 class="text-3xl font-bold text-center mb-6">
                    {{ t.contactUsTitle }}
                </h2>
                <p class="text-xl text-center mb-8">{{ t.contactUsMessage }}</p>
                <div class="space-y-6">
                    <!-- GitHub Section -->
                    <a
                        href="https://github.com/CHILCardiff/"
                        target="_blank"
                        class="flex items-center p-4 border-2 border-blue-600 rounded-lg hover:bg-blue-50"
                    >
                        <img
                            src="../../../public/Images/ContactUs/Github-desktop-logo-symbol.svg.png"
                            alt="GitHub"
                            class="w-8 h-8 mr-4"
                        />
                        <div>
                            <h3 class="text-2xl font-semibold">
                                {{ t.githubTitle }}
                            </h3>
                            <p class="text-lg">{{ t.githubDescription }}</p>
                        </div>
                    </a>
                    <!-- LinkedIn Section -->
                    <a
                        href="https://www.linkedin.com/in/michael-prior-jones/"
                        target="_blank"
                        class="flex items-center p-4 border-2 border-blue-600 rounded-lg hover:bg-blue-50"
                    >
                        <img
                            src="../../../public/Images/ContactUs/LinkedIn_icon.svg.png"
                            alt="LinkedIn"
                            class="w-8 h-8 mr-4"
                        />
                        <div>
                            <h3 class="text-2xl font-semibold">
                                {{ t.linkedinTitle }}
                            </h3>
                            <p class="text-lg">{{ t.linkedinDescription }}</p>
                        </div>
                    </a>
                    <!-- Mastodon Section -->
                    <a
                        href="https://mastodon.social/@drmikepj"
                        target="_blank"
                        class="flex items-center p-4 border-2 border-blue-600 rounded-lg hover:bg-blue-50"
                    >
                        <img
                            src="../../../public/Images/ContactUs/Mastodon_Logotype_(Simple).svg.png"
                            alt="Mastodon"
                            class="w-8 h-8 mr-4"
                        />
                        <div>
                            <h3 class="text-2xl font-semibold">
                                {{ t.mastodonTitle }}
                            </h3>
                            <p class="text-lg">{{ t.mastodonDescription }}</p>
                        </div>
                    </a>
                    <!-- Bluesky Section -->
                    <a
                        href="https://bsky.app/profile/drmikepj.bsky.social"
                        target="_blank"
                        class="flex items-center p-4 border-2 border-blue-600 rounded-lg hover:bg-blue-50"
                    >
                        <img
                            src="../../../public/Images/ContactUs/Bluesky_Logo.svg.png"
                            alt="Bluesky"
                            class="w-8 h-8 mr-4"
                        />
                        <div>
                            <h3 class="text-2xl font-semibold">
                                {{ t.blueskyTitle }}
                            </h3>
                            <p class="text-lg">{{ t.blueskyDescription }}</p>
                        </div>
                    </a>
                    <!-- Cardiff University Section -->
                    <a
                        href="https://profiles.cardiff.ac.uk/staff/prior-jonesm"
                        target="_blank"
                        class="flex items-center p-4 border-2 border-blue-600 rounded-lg hover:bg-blue-50"
                    >
                        <img
                            src="../../../public/Images/ContactUs/Cardiff_University_(logo).svg.png"
                            alt="Cardiff University"
                            class="w-8 h-8 mr-4"
                        />
                        <div>
                            <h3 class="text-2xl font-semibold">
                                {{ t.cardiffUniversityTitle }}
                            </h3>
                            <p class="text-lg">
                                {{ t.cardiffUniversityDescription }}
                            </p>
                        </div>
                    </a>
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>
/* You can add custom styles here if needed */
</style>
