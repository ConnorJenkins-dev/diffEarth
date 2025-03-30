<template>
    <div class="p-6 bg-white shadow-lg rounded-lg w-96">
        <h2 class="text-xl font-semibold mb-4">Assign Roles to User</h2>

        <!-- Select User -->
        <label class="block font-medium mb-1">Select User</label>
        <select v-model="selectedEmail" class="w-full p-2 border rounded">
            <option value="" disabled>Select an email</option>
            <option v-for="email in emails" :key="email" :value="email">
                {{ email }}
            </option>
        </select>

        <!-- Select Role(s) -->
        <label for="roles" class="block font-medium mt-3 mb-1"
            >Select Roles:</label
        >
        <div v-for="(roleInput, index) in roleInputs" :key="index" class="mb-4">
            <select
                v-model="roleInput.role"
                @change="addRoleInput"
                class="w-full p-2 border rounded"
            >
                <option value="" disabled>Select a role</option>
                <option v-for="role in roles" :key="role" :value="role">
                    {{ role }}
                </option>
            </select>

            <!-- Button to remove the role input -->
            <button
                v-if="roleInputs.length > 1"
                @click="removeRoleInput(index)"
                class="text-red-500 mt-2"
            >
                Remove Role
            </button>
        </div>

        <!-- Submit Button -->
        <button
            @click="updateRoles"
            class="w-full bg-blue-500 text-white p-2 rounded mt-4 hover:bg-blue-600"
        >
            Update Roles
        </button>

        <!-- Feedback Message -->
        <p v-if="message" class="mt-3 text-green-500">{{ message }}</p>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

const emails = ref([]);
const selectedEmail = ref("");
const selectedRoles = ref([]); // Array to store selected roles
const roles = ref([]); // Available roles from the backend
const message = ref("");
const roleInputs = ref([{ role: "" }]); // Initialize with one empty role input

// Fetch all users' emails and roles from the API
onMounted(async () => {
    try {
        const response = await fetch("/api/users/emails");
        emails.value = await response.json();
    } catch (error) {
        console.error("Error fetching emails:", error);
    }

    try {
        const response = await fetch("/api/roles");
        roles.value = await response.json();
    } catch (error) {
        console.error("Error fetching roles:", error);
    }
});

// Add a new role input field
const addRoleInput = () => {
    const lastRoleInput = roleInputs.value[roleInputs.value.length - 1];
    if (lastRoleInput && lastRoleInput.role) {
        // Only add a new role input if the last one has a role selected
        roleInputs.value.push({ role: "" });
    }
};

// Remove a role input field
const removeRoleInput = (index) => {
    roleInputs.value.splice(index, 1);
};

// Function to update roles for a user
const updateRoles = async () => {
    message.value = "";

    // Collect all selected roles
    const rolesToUpdate = roleInputs.value
        .map((input) => input.role)
        .filter((role) => role);

    if (!selectedEmail.value || rolesToUpdate.length === 0) {
        message.value = "Please select a user and at least one role.";
        return;
    }

    try {
        const response = await fetch("/api/users/update-role", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                email: selectedEmail.value,
                roles: rolesToUpdate,
            }),
        });

        const result = await response.json();

        if (response.ok) {
            message.value = "Roles updated successfully!";
        } else {
            throw new Error(result.message || "Failed to update roles.");
        }
    } catch (error) {
        console.error("Error updating roles:", error);
        message.value = error.message;
    }
};
</script>

<style scoped>
/* Add any styles you need for the form */
</style>
