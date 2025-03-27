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

// Fetch GeoJSON for polygons
async function fetchGeoJson(url, retries = 5, delay = 1000) {
    for (let attempt = 1; attempt <= retries; attempt++) {
        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(
                    `Attempt ${attempt} failed: ${response.statusText}`,
                );
            }
            return await response.json();
        } catch (error) {
            console.error(
                `Error fetching GeoJSON (attempt ${attempt}):`,
                error.message,
            );
            if (attempt === retries) {
                throw new Error(
                    `Could not fetch countries.geojson after ${retries} attempts`,
                );
            }
            await new Promise((resolve) => setTimeout(resolve, delay)); // Wait before retrying
        }
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
    try {
        const countries = await fetchGeoJson("/countries.geojson", 5, 1000);

        myGlobe.value = Globe()(globeDiv.value)
            .globeImageUrl("/white.png")
            .backgroundImageUrl("/white.png")
            .polygonsData(countries.features)
            .polygonCapColor((feat) =>
                feat.properties.admin === "Greenland" ? "#00adff" : "#ffffff",
            )
            .polygonStrokeColor(() => "#0a1195")
            .polygonSideColor(() => "#D3D3D3")
            .polygonAltitude(0.02)
            .onPolygonHover((hoveredCountry) => {
                if (myGlobe.value) {
                    myGlobe.value.polygonStrokeColor((feat) =>
                        feat === hoveredCountry ? "#ff4500" : "#0a1195",
                    );
                    myGlobe.value.polygonAltitude((feat) =>
                        feat === hoveredCountry ? 0.1 : 0.06,
                    );
                }
            })
            .polygonsTransitionDuration(200)
            .onPolygonClick(() => {
                if (myGlobe.value) {
                    myGlobe.value.pointOfView(
                        { lat: latitude, lng: longitude, altitude: 0.7 },
                        2000,
                    );
                    // Add a marker dynamically
                    markers.value.push({
                        lat: latitude,
                        lng: longitude,
                        size: 10,
                    });
                    myGlobe.value.pointsData(markers.value);
                }
            });

        addEventListeners();

        // Ensure markers are visible in polygon mode
        if (markers.value.length > 0 && myGlobe.value) {
            myGlobe.value.pointsData(markers.value);
        }
    } catch (error) {
        console.error("Error initializing polygon version:", error.message);
    }
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
