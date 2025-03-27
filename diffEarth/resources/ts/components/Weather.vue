<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";

const weatherData = ref(null);
const latLong = ref(null);

// Get the UUID from the route
const route = useRoute();
const uuid = route.params.uuid;

// Fetch the latitude and longitude for the deployment
const fetchLatLong = async () => {
    try {
        const response = await fetch(`/api/deployments/${uuid}/latlong`, {
            method: "GET", // Make sure the method is GET
        });
        const data = await response.json();
        latLong.value = data; // Store lat and long in latLong
        fetchWeather(latLong.value.lat, latLong.value.long); // Fetch weather data with lat, lon
    } catch (error) {
        console.error("Error fetching lat/long:", error);
    }
};

// Fetch weather data using dynamic latitude and longitude
const fetchWeather = async (lat, lon) => {
    try {
        const response = await fetch(`/api/weather?lat=${lat}&lon=${lon}`);
        weatherData.value = await response.json();
    } catch (error) {
        console.error("Error fetching weather:", error);
    }
};

onMounted(fetchLatLong);
</script>

<template>
    <div v-if="weatherData" class="weather-card">
        <h2>Weather</h2>
        <img
            :src="weatherData.icon"
            :alt="weatherData.description"
            class="gcenter"
        />
        <p><strong>Temperature:</strong> {{ weatherData.temperature }}°C</p>
        <p><strong>Condition:</strong> {{ weatherData.condition }}</p>
        <p><strong>Description:</strong> {{ weatherData.description }}</p>
    </div>
    <p v-else>Loading weather...</p>
</template>

<style scoped>
.weather-card {
    border: 1px solid #ddd;
    padding: 1rem;
    border-radius: 8px;
    max-width: 300px;
    text-align: center;
    background: #0f0f53;
    color: white;
}
</style>
