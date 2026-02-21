<template>
    <div ref="globeDiv"></div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import Globe from "globe.gl";

const globeDiv = ref(null);
const myGlobe = ref(null);
const markers = ref([]);

const emit = defineEmits(["deploymentClick"]);

const preventScroll = (event) => {
    event.preventDefault();
};

async function fetchDeployments() {
    try {
        const response = await fetch(
            `${import.meta.env.BASE_URL}api/deployments`,
        );
        if (!response.ok) throw new Error("Failed to fetch deployments");
        const data = await response.json();

        markers.value = data.map((deployment) => ({
            lat: deployment.latitude,
            lng: deployment.longitude,
            size: 0.01,
            id: deployment.id,
            uid: deployment.uid,
            name: deployment.name,
        }));

        if (myGlobe.value) {
            myGlobe.value.pointsData(markers.value);
        }
    } catch (error) {
        console.error("Error fetching deployments:", error.message);
    }
}

onMounted(async () => {
    if (!globeDiv.value) return;

    globeDiv.value.addEventListener("wheel", preventScroll, { passive: false });
    globeDiv.value.addEventListener("mousedown", (event) => {
        if (event.button === 1) event.preventDefault();
    });

    myGlobe.value = Globe()(globeDiv.value)
        .globeTileEngineUrl(
            (x, y, l) => `https://tile.openstreetmap.org/${l}/${x}/${y}.png`,
        )
        .backgroundImageUrl("white.png")
        .pointsData(markers.value)
        .pointAltitude(0.02)
        .pointColor(() => "rgba(15,12,167,0.85)")
        .onPointClick((point) => {
            if (point.uid) emit("deploymentClick", point.uid);
            myGlobe.value.pointOfView(
                { lat: point.lat, lng: point.lng, altitude: 0.7 },
                2000,
            );
            myGlobe.value.controls().autoRotate = false;
        })
        .onPointHover((point) => {
            myGlobe.value.pointAltitude((p) => (p === point ? 0.12 : 0.02));
        })
        .pointLabel((d) => d.name); // Tooltip label

    myGlobe.value.controls().autoRotate = true;
    myGlobe.value.controls().autoRotateSpeed = 0.5;

    await fetchDeployments();

    onUnmounted(() => {
        globeDiv.value.removeEventListener("wheel", preventScroll);
    });
});

function addMarker(lat, lng, id) {
    markers.value.push({ lat, lng, size: 0.01, id });
    if (myGlobe.value) {
        myGlobe.value.pointsData(markers.value);
    }
}

defineExpose({
    myGlobe,
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
