<script setup lang="ts">
import Header from "./Header.vue";
import { ref } from "vue";
import { useTranslation } from "../composables/useTranslation";
import ImageSlider from "../components/ImageSlider.vue";
const { t } = useTranslation();

const cryoeggImages = ref([
    "/Images//Resources/egg1.jpg",
    "/Images//Resources/egg2.jpg",
    "/Images//Resources/egg3.jpg",
]);

const cryowurstImages = ref([
    "/Images/Resources/wurst1.jpg",
    "/Images/Resources/wurst2.jpg",
]);

const cryoeggMade = ref([
    "/Images//Resources/eggmade1.png",
    "/Images//Resources/eggmade2.png",
]);
const surfaceReceiverImages = ref([
    "/Images/Resources/surfsetup.png",
    "/Images/Resources/greenlandreceiver1.jpg",
    "/Images/Resources/greenlandreceiver2.jpg",
    "/Images/Resources/greenlandreceiver3.jpg",
    "/Images/Resources/yukon_mike_receiver.jpg",
    "/Images/Resources/datalogger.jpg",
]);

const cryowurstDeployImages = ref([
    "/Images/Resources/wurstdep.jpg",
    "/Images/Resources/yukon_happy_wurst.jpg",
    "/Images/Resources/yukon_wurst_deployment.jpg",
]);
const cryoeggDeployImage = "/Images/Resources/eggdep.jpg";
const cryowurstMadeImage = "/Images/Resources/wurstmade.png";
</script>
<template>
    <Header />
    <div class="flex flex-col items-center w-full bg-[var(--greyBlue)] px-4">
        <section
            class="w-full max-w-[20000px] mx-auto border-2 border-[var(--darkBlue)] bg-white rounded-lg shadow-md p-6"
        >
            <h2 class="text-6xl font-bold text-center mb-12">
                {{ t.resourcesTitle }}
            </h2>
            <p class="text-2xl text-center mb-16">{{ t.resourcesIntro }}</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                <div class="w-full md:w-3/4 mx-auto">
                    <h3 class="text-4xl font-semibold text-center mb-6">
                        {{ t.cryoeggTitle }}
                    </h3>
                    <ImageSlider :images="cryoeggImages" />
                </div>
                <div class="w-full md:w-3/4 mx-auto">
                    <h3 class="text-4xl font-semibold text-center mb-6">
                        {{ t.cryowurstTitle }}
                    </h3>
                    <ImageSlider :images="cryowurstImages" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 mt-16">
                <div class="w-full md:w-3/4 mx-auto">
                    <h3 class="text-4xl font-semibold text-center mb-6">
                        {{ t.cryoeggMadeTitle }}
                    </h3>
                    <ImageSlider :images="cryoeggMade" />
                </div>
                <div class="w-full md:w-3/4 mx-auto">
                    <h3 class="text-4xl font-semibold text-center mb-6">
                        {{ t.cryowurstMadeTitle }}
                    </h3>
                    <div class="w-full overflow-hidden rounded-lg">
                        <img
                            :src="cryowurstMadeImage"
                            class="w-full object-cover"
                            alt="Crywurst made"
                        />
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 mt-16">
                <div class="w-full md:w-3/4 mx-auto">
                    <h3 class="text-4xl font-semibold text-center mb-6">
                        {{ t.cryoeggDeployment }}
                    </h3>
                    <div class="w-full overflow-hidden rounded-lg">
                        <img
                            :src="cryoeggDeployImage"
                            class="w-full object-cover"
                            alt="Cryoegg Deployment"
                        />
                    </div>
                </div>
                <div class="w-full md:w-3/4 mx-auto">
                    <h3 class="text-4xl font-semibold text-center mb-6">
                        {{ t.cryowurstDeployment }}
                    </h3>
                    <div class="w-full overflow-hidden rounded-lg">
                        <ImageSlider :images="cryowurstDeployImages" />
                    </div>
                </div>
            </div>

            <div class="mt-16 text-center">
                <h3 class="text-4xl font-semibold mb-8">
                    {{ t.surfaceReceiver }}
                </h3>
                <div class="w-1/2 mx-auto overflow-hidden rounded-lg">
                    <ImageSlider :images="surfaceReceiverImages" />
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped></style>
