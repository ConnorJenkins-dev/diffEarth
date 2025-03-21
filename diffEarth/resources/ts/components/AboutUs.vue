<script setup lang="ts">
import Header from "./Header.vue";
import AboutCard from "./AboutCard.vue";
import AboutBio from "./AboutBio.vue";
import { useTranslation } from "../composables/useTranslation";
const { t } = useTranslation();
</script>

<template>
    <Header />
    <div class="flex flex-row mx-5 bg-[#FAFAFA] rounded-sm">
        <section class="w-3/5 shadow">
            <div class="flex justify-center">
                <h1
                    class="underline text-3xl font-bold font-sans italic text-gray-800"
                >
                    {{ t.aboutUs }}
                </h1>
            </div>
            <div class="flex flex-col items-center">
                <AboutCard
                    imgMain="https://www.onthegotours.com/repository/Greenland-in-summer--Best-Time-to-Visit--On-The-Go-Tours-328241481727578_crop_610_410.jpg"
                    heading="Example Heading 1"
                    content="This is a small about us sectuon. It is a placeholder for the real content that will be added later. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla nec purus feugiat, molestie ipsum et, eleifend nunc. Nulla facilisi. Nullam vel nisi nec nunc gravida ultricies. Nullam nec nunc nec nunc gravida ultricies."
                    imgProfile="https://media.istockphoto.com/id/1413766112/photo/successful-mature-businessman-looking-at-camera-with-confidence.jpg?s=612x612&w=0&k=20&c=NJSugBzNuZqb7DJ8ZgLfYKb3qPr2EJMvKZ21Sj5Sfq4="
                    name="John Doe"
                    date="24/02/2025"
                />
                <AboutCard />
                <AboutCard />
            </div>
        </section>
        <aside class="w-2/5 ml-5">
            <AboutBio />
        </aside>
    </div>
</template>

<style scoped></style>
