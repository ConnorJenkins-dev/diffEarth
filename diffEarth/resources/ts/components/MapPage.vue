<script setup>
import { ref } from "vue";
import Header from "./Header.vue";
import GlobeComponent from "./GlobeComponent.vue";
import GlobeComponent2 from "./GlobeComponent2.vue";
import DeploymentView from "./DeploymentView.vue";
import AddDeployment from "./AddDeployment.vue";

const globeComponent = ref(null); // Reference for the active globe component
const currentGlobe = ref(1); // Track which globe component to display (1 or 2)

// Function to load Polygon globe
function loadGlobeComponent() {
    currentGlobe.value = 1;
}

// Function to load Map globe
function loadGlobeComponent2() {
    currentGlobe.value = 2;
}
</script>

<template>
    <Header />
    <main class="flex flex-col w-full h-screen">
        <!-- Container for the globe and deployment form -->
        <section id="globe-container" class="flex h-full">
            <!-- Deployment Form (on the left, wider) -->
            <aside
                id="deployment-form"
                class="w-2/5 p-4 bg-gray-100 shadow-md overflow-y-auto transition-width duration-300"
            >
                <!-- Pass the correct globe reference to DeploymentView -->
                <DeploymentView :globeRef="globeComponent" />
                <!--<AddDeployment globe-ref="globeComponent" />-->

                <!-- Buttons to Load Globe Components -->
                <div class="sticky top-0 z-10 bg-gray-200 p-4">
                    <button
                        @click="loadGlobeComponent"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md mr-2 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                        Poly Globe
                    </button>
                    <button
                        @click="loadGlobeComponent2"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                        Map Globe
                    </button>
                </div>
            </aside>

            <!-- Dynamically Rendered Globe Component (on the right, taking up remaining space) -->
            <section id="globe" class="flex-grow relative overflow-hidden">
                <GlobeComponent
                    v-if="currentGlobe === 1"
                    ref="globeComponent"
                    class="h-full w-full"
                />
                <GlobeComponent2
                    v-else
                    ref="globeComponent"
                    class="h-full w-full"
                />
            </section>
        </section>
    </main>
</template>

<style scoped>
/* Style for the globe container */
#globe-container {
    display: flex;
    width: 100%;
    height: 100%;
}

/* Style for the deployment form */
#deployment-form {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: stretch;
    height: 100%;
    overflow-y: auto;
    max-width: 100%;
}

/* Style for the globe */
#globe {
    width: 100%;
    height: 100%;
    overflow: hidden;
}

/* Button Styling */
button {
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

button:hover {
    cursor: pointer;
}
</style>
