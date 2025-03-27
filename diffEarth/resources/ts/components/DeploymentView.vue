<template>
    <div class="h-full flex flex-col">
        <!-- Sticky Search Bar -->
        <header
            class="sticky top-0 z-10 bg-indigo-600 text-white shadow-md p-4 border-b border-indigo-500"
        >
            <div class="flex items-center justify-between">
                <!-- Search Input -->
                <div class="relative w-full md:w-1/2">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search by name..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-700"
                    />
                    <span
                        v-if="searchQuery"
                        @click="clearSearch"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-gray-500 hover:text-red-500"
                    >
                        &times;
                    </span>
                </div>

                <!-- Filter Dropdowns -->
                <div class="flex space-x-4 items-center">
                    <!-- Sort Dropdown -->
                    <select
                        v-model="sortOrder"
                        class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-700"
                    >
                        <option value="asc">Sort A → Z</option>
                        <option value="desc">Sort Z → A</option>
                    </select>
                </div>
            </div>
        </header>

        <!-- Scrollable Deployment List -->
        <main class="flex-1 overflow-y-auto p-4 space-y-4">
            <div
                v-for="deployment in filteredDeployments"
                :key="deployment.id"
                class="p-4 bg-indigo-100 border border-indigo-300 rounded-lg shadow-sm flex items-center justify-between"
            >
                <!-- Limited Details -->
                <div>
                    <h2 class="text-lg font-medium text-indigo-800">
                        {{ deployment.name }}
                    </h2>
                    <p class="text-sm text-gray-600">
                        <strong>UID:</strong> {{ deployment.uid }}
                    </p>
                </div>

                <!-- Go to Location Button -->
                <button
                    @click="goToLocation(deployment)"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    Go to Location
                </button>
            </div>

            <!-- No Results Message -->
            <div
                v-if="filteredDeployments.length === 0"
                class="text-center text-gray-500"
            >
                <p>No deployments found.</p>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from "vue";

// Props to receive the globe component's reference
const props = defineProps({
    globeRef: {
        type: Object,
        required: true,
    },
});

// Reactive state
const deployments = ref([]);
const searchQuery = ref("");
const sortOrder = ref("asc");

// Fetch deployments from the API using fetch
async function fetchDeployments() {
    try {
        const response = await fetch("/api/deployments");
        if (!response.ok) {
            throw new Error(
                `Error fetching deployments: ${response.statusText}`,
            );
        }
        const data = await response.json();
        deployments.value = data; // Assuming the API returns an array of deployments
    } catch (error) {
        console.error("Error fetching deployments:", error.message);
    }
}

// Computed property for filtering and sorting
const filteredDeployments = computed(() => {
    return deployments.value
        .filter((deployment) =>
            deployment.name
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase()),
        )
        .sort((a, b) => {
            const nameA = a.name.toUpperCase();
            const nameB = b.name.toUpperCase();

            if (sortOrder.value === "asc") {
                return nameA.localeCompare(nameB);
            } else {
                return nameB.localeCompare(nameA);
            }
        });
});

// Clear search query
function clearSearch() {
    searchQuery.value = "";
}

// Go to Location Functionality
function goToLocation(deployment) {
    if (!props.globeRef || !props.globeRef.myGlobe) {
        console.error("Globe component not initialized.");
        return;
    }

    const { latitude, longitude } = deployment;

    // Safeguard against missing pointOfView method
    if (
        props.globeRef.myGlobe &&
        typeof props.globeRef.myGlobe.pointOfView === "function"
    ) {
        props.globeRef.myGlobe.pointOfView(
            { lat: latitude, lng: longitude, altitude: 0.2 },
            2000, // Transition duration in milliseconds
        );
    } else {
        console.error("pointOfView method not available on globe instance.");
    }

    // Safeguard against missing controls method
    if (
        props.globeRef.myGlobe &&
        typeof props.globeRef.myGlobe.controls === "function"
    ) {
        props.globeRef.myGlobe.controls().autoRotate = false;
    } else {
        console.warn("Controls method not available in current mode.");
    }
}

// Lifecycle hook
onMounted(async () => {
    await fetchDeployments();

    // Watch for changes in globeRef and wait until it becomes valid
    watch(
        () => props.globeRef,
        (newGlobeRef) => {
            if (newGlobeRef && newGlobeRef.myGlobe) {
                console.log("Globe instance updated:", newGlobeRef.myGlobe);
            } else {
                console.warn("Globe instance is not yet available.");
            }
        },
        { immediate: true },
    );
});
</script>

<style scoped>
/* Ensure no gaps between components */
main {
    height: calc(100vh - 12rem); /* Adjust based on header height */
}

/* Hover effect for deployment cards */
.bg-indigo-100:hover {
    background-color: #dbeafe; /* Lighter shade of indigo */
}
</style>
