<template>
    <div ref="globeDiv"></div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import Globe from "globe.gl";

const globeDiv = ref(null);
const myGlobe = ref(null);
const markers = ref([]);

// Function to prevent page scroll when interacting with the globe
const preventScroll = (event) => {
    event.preventDefault();
};

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
        markers.value = data.map((deployment) => ({
            lat: deployment.latitude,
            lng: deployment.longitude,
            size: 0.01,
            id: deployment.id,
        }));

        if (myGlobe.value) {
            myGlobe.value.pointsData(markers.value); // Update markers on the globe
        }
    } catch (error) {
        console.error("Error fetching deployments:", error.message);
    }
}

// Mounting logic
onMounted(async () => {
    if (!globeDiv.value) return;

    // Prevent page scroll when interacting with the globe
    globeDiv.value.addEventListener("wheel", preventScroll, { passive: false });
    globeDiv.value.addEventListener("mousedown", (event) => {
        // Prevent middle mouse button scrolling
        if (event.button === 1) event.preventDefault();
    });

    // Initialize the globe
    myGlobe.value = Globe()(globeDiv.value)
        .globeTileEngineUrl(
            (x, y, l) => `https://tile.openstreetmap.org/${l}/${x}/${y}.png`,
        )
        .backgroundImageUrl("/white.png")
        .pointsData(markers.value) // Add markers dynamically
        .pointAltitude(0.02) // Raise markers slightly above the globe
        .pointColor(() => "rgba(15,12,167,0.85)") // Red color with some opacity
        .onPointClick((point) => {
            // Zoom in on the clicked marker
            myGlobe.value.pointOfView(
                { lat: point.lat, lng: point.lng, altitude: 0.7 },
                2000, // Transition duration in milliseconds
            );

            // Stop autorotation
            myGlobe.value.controls().autoRotate = false;
        });
    // Enable globe autorotation initially
    myGlobe.value.controls().autoRotate = true;
    myGlobe.value.controls().autoRotateSpeed = 0.5;

    // Fetch initial deployments
    await fetchDeployments();

    // Cleanup listener on unmount
    onUnmounted(() => {
        globeDiv.value.removeEventListener("wheel", preventScroll);
    });
});

// Function to add a new marker dynamically
function addMarker(lat, lng, id) {
    markers.value.push({ lat, lng, size: 0.001, id });
    if (myGlobe.value) {
        myGlobe.value.pointsData(markers.value); // Update markers on the globe
    }
}

// Expose methods for external updates
defineExpose({
    myGlobe, // Expose the globe instance
    updateMarkers: (newDeployment) => {
        addMarker(
            newDeployment.latitude,
            newDeployment.longitude,
            newDeployment.id,
        );
    },
});
</script>

<style scoped>
#globeDiv {
    width: 100vw;
    height: 100vh;
}
</style>
