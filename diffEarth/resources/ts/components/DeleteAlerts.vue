<script setup>
import { ref, onMounted } from "vue";

const alerts = ref([]);
const selectedAlert = ref(null);
const showModal = ref(false);

// Fetch all alerts from the database
const fetchAlerts = async () => {
    try {
        const response = await fetch("/api/alerts"); // Adjust endpoint if needed
        if (!response.ok) throw new Error("Failed to fetch alerts");
        alerts.value = await response.json();
    } catch (error) {
        console.error(error);
    }
};

// Open confirmation modal
const confirmDelete = (alert) => {
    selectedAlert.value = alert;
    showModal.value = true;
};

// Close the modal
const closeModal = () => {
    showModal.value = false;
    selectedAlert.value = null;
};

// Delete the selected alert
const deleteAlert = async () => {
    if (!selectedAlert.value) return;

    try {
        const response = await fetch(`/api/alerts/${selectedAlert.value.id}`, {
            method: "DELETE",
        });

        const data = await response.json();
        if (response.ok) {
            // Successfully deleted, remove from UI
            alerts.value = alerts.value.filter(
                (alert) => alert.id !== selectedAlert.value.id,
            );
            alert(data.message); // Show success message

            // Re-fetch alerts and close the modal
            fetchAlerts();
            closeModal();
        } else {
            alert(data.message || "Failed to delete alert");
        }
    } catch (error) {
        console.error("Error deleting alert:", error);
        alert("Failed to delete alert");
    }
};

onMounted(fetchAlerts);
</script>

<template>
    <div class="p-6 bg-white rounded-lg w-full max-w-lg">
        <h2 class="text-lg font-semibold mb-4">Manage Alerts</h2>

        <div v-if="alerts.length === 0" class="text-gray-500">
            No alerts available.
        </div>

        <ul v-else class="border rounded p-2 mb-4">
            <li
                v-for="alert in alerts"
                :key="alert.id"
                class="p-2 flex justify-between border-b"
            >
                <span
                    >{{ alert.location }} - {{ alert.column }} ({{
                        alert.threshold
                    }})</span
                >
                <button
                    @click="confirmDelete(alert)"
                    class="text-red-500 hover:underline"
                >
                    Delete
                </button>
            </li>
        </ul>
    </div>

    <div
        v-if="showModal"
        class="fixed inset-0 bg-gray-800/50 flex items-center justify-center"
    >
        <div
            class="bg-white p-6 rounded-lg shadow-lg text-center relative z-10"
        >
            <p class="mb-4">Are you sure you want to delete this alert?</p>
            <div class="flex justify-center gap-4">
                <button
                    @click="deleteAlert"
                    class="bg-red-500 text-white px-4 py-2 rounded"
                >
                    Yes
                </button>
                <button
                    @click="closeModal"
                    class="bg-gray-300 px-4 py-2 rounded"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
button {
    cursor: pointer;
}
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}
.modal-enter,
.modal-leave-to {
    opacity: 0;
}
</style>
