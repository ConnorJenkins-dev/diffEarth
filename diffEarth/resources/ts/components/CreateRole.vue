<script setup>
import { ref } from "vue";

const roleName = ref("");
const message = ref("");
const roles = ref([]);

// Fetch existing roles
const fetchRoles = async () => {
    try {
        const response = await fetch("/api/roles");
        if (!response.ok) throw new Error("Failed to fetch roles.");
        roles.value = await response.json();
        console.log(roles);
    } catch (error) {
        console.error("Error fetching roles:", error);
        message.value = "Error loading roles.";
    }
};

const createRole = async () => {
    await fetchRoles();
    message.value = "";

    if (!roleName.value) {
        message.value = "Role name cannot be empty.";
        return;
    }

    try {
        await fetchRoles(); // Ensure roles are fetched before checking

        // Ensure roles are loaded
        if (!roles.value || roles.value.length === 0) {
            message.value = "Failed to fetch roles. Try again.";
            return;
        }

        if (/\s/.test(roleName.value)) {
            message.value = "Role name cannot contain spaces.";
            return;
        }

        const existingRoleNames = roles.value;
        const newRoleName = roleName.value.toString().toLocaleLowerCase();
        console.log("existing role names: ", existingRoleNames);
        console.log("new role name: ", newRoleName);

        if (existingRoleNames.includes(newRoleName)) {
            message.value = "Role already exists!";
            return;
        }

        // Proceed with creating the role
        const response = await fetch("/api/roles/add-role", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ name: newRoleName }),
        });

        const result = await response.json();

        if (response.ok) {
            message.value = "Role created successfully!";
            roleName.value = ""; // Clear input after success
            await fetchRoles(); // Refresh roles after creation
        } else {
            throw new Error(result.message || "Failed to create role.");
        }
    } catch (error) {
        console.error("Error creating role:", error);
        message.value = error.message;
    }
};
</script>

<template>
    <div class="p-6 bg-white shadow-lg rounded-lg w-96">
        <h2 class="text-xl font-semibold mb-4">Create New Role</h2>

        <label class="block font-medium mb-1">Role Name</label>
        <input
            v-model="roleName"
            type="text"
            class="w-full p-2 border rounded"
            placeholder="Enter role name"
        />

        <button
            @click="createRole"
            class="w-full bg-green-500 text-white p-2 rounded mt-4 hover:bg-green-600"
        >
            Create Role
        </button>

        <p v-if="message" class="mt-3 text-red-500">{{ message }}</p>
    </div>
</template>
