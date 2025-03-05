<script setup>
import { ref, onMounted } from "vue";
import Globe from "globe.gl";

const globeDiv = ref(null);
const myGlobe = ref(null);
const markers = ref([]);

async function fetchWithRetry(url, retries = 5, delay = 1000) {
    for (let attempt = 1; attempt <= retries; attempt++) {
        try {
            const response = await fetch(url);
            if (!response.ok)
                throw new Error(
                    `Attempt ${attempt} failed: ${response.statusText}`,
                );
            return await response.json();
        } catch (error) {
            console.error(
                `Error fetching GeoJSON (attempt ${attempt}):`,
                error,
            );
            if (attempt === retries)
                throw new Error(
                    `Could not fetch countries.geojson after ${attempt} attempts`,
                );
            await new Promise((resolve) => setTimeout(resolve, delay)); // Wait before retrying
        }
    }
}

onMounted(async () => {
    if (!globeDiv.value) return;

    try {
        const countries = await fetchWithRetry("/countries.geojson", 5, 1000);

        myGlobe.value = Globe()(globeDiv.value)
            .globeImageUrl("/white.png")
            .backgroundImageUrl("/white.png")
            .lineHoverPrecision(0)
            .polygonsData(countries.features)
            .polygonCapColor((feat) =>
                feat.properties.admin === "Greenland" ? "#00adff" : "#ffffff",
            ) //colour on top of country
            .polygonStrokeColor(() => "#0a1195") //colour of borders
            .polygonSideColor(() => "#D3D3D3") //colour on side
            .polygonAltitude(0.02)
            .onPolygonHover((hoveredCountry) => {
                myGlobe.value
                    .polygonStrokeColor((feat) =>
                        feat === hoveredCountry ? "#ff4500" : "#0000ff",
                    ) // change to orange border on hover
                    .polygonAltitude((feat) =>
                        feat === hoveredCountry ? 0.1 : 0.06,
                    ); // raise altitude on hover
            })
            .polygonsTransitionDuration(200)
            .onPolygonClick((feat) => {
                if (feat.properties.admin !== "Greenland") return;
                const latitude = feat.properties.latitude;
                const longitude = feat.properties.longitude;
                console.log(`zooming in to: ${feat.properties.admin}`);

                myGlobe.value.pointOfView(
                    { lat: latitude, lng: longitude, altitude: 0.7 },
                    2000,
                );

                const newMarker = {
                    lat: 72, // Random latitude near Greenland
                    lng: -40, // Random longitude near Greenland
                    size: 10,
                };

                markers.value.push(newMarker);
                myGlobe.value.pointsData(markers.value);
            });
    } catch (error) {
        console.error("Error loading GeoJSON:", error);
    }
});
</script>

<template>
    <div ref="globeDiv"></div>
</template>

<style scoped></style>
