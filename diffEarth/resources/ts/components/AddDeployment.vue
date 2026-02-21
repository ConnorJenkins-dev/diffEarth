<template>
    <div class="flex flex-col w-full max-h-full overflow-auto px-4 pb-4">
        <!-- Success/Error Messages -->
        <div v-if="successMessage" class="text-green-500 mb-4">
            {{ successMessage }}
        </div>
        <div v-if="errorMessage" class="text-red-500 mb-4">
            {{ errorMessage }}
        </div>

        <!-- Deployment Form -->
        <form
            @submit.prevent="submitDeployment"
            class="space-y-4 flex flex-col flex-grow"
        >
            <!-- Name Field -->
            <div>
                <label
                    for="name"
                    class="block text-sm font-medium text-gray-700"
                    >Name:</label
                >
                <input
                    id="name"
                    v-model.trim="newDeployment.name"
                    type="text"
                    required
                    :class="[
                        'mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                        { 'border-red-500': v$.name.$error },
                    ]"
                    aria-invalid="true"
                    aria-describedby="name-error"
                />
                <p
                    v-if="v$.name.$error"
                    id="name-error"
                    class="text-xs text-red-500 mt-1"
                >
                    {{ v$.name.$errors[0]?.$message }}
                </p>
            </div>

            <!-- Latitude and Longitude -->
            <div class="flex flex-row items-center justify-left">
                <!-- Latitude Field -->
                <div>
                    <label
                        for="latitude"
                        class="block text-sm font-medium text-gray-700"
                        >Latitude:</label
                    >
                    <input
                        id="latitude"
                        v-model.number="newDeployment.latitude"
                        type="number"
                        step="any"
                        required
                        :class="[
                            'mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                            { 'border-red-500': v$.latitude.$error },
                        ]"
                        aria-invalid="true"
                        aria-describedby="latitude-error"
                    />
                    <p
                        v-if="v$.latitude.$error"
                        id="latitude-error"
                        class="text-xs text-red-500 mt-1"
                    >
                        {{ v$.latitude.$errors[0]?.$message }}
                    </p>
                </div>

                <!-- Longitude Field -->
                <div>
                    <label
                        for="longitude"
                        class="block text-sm font-medium text-gray-700"
                        >Longitude:</label
                    >
                    <input
                        id="longitude"
                        v-model.number="newDeployment.longitude"
                        type="number"
                        step="any"
                        required
                        :class="[
                            'mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                            { 'border-red-500': v$.longitude.$error },
                        ]"
                        aria-invalid="true"
                        aria-describedby="longitude-error"
                    />
                    <p
                        v-if="v$.longitude.$error"
                        id="longitude-error"
                        class="text-xs text-red-500 mt-1"
                    >
                        {{ v$.longitude.$errors[0]?.$message }}
                    </p>
                </div>
            </div>

            <!-- Description Field -->
            <div>
                <label
                    for="description"
                    class="block text-sm font-medium text-gray-700"
                    >Description:</label
                >
                <p class="text-xs text-grey">
                    Brief description about dashboard. Will appear in the list
                    of deployments. Separate from dashboard info.
                </p>
                <textarea
                    id="description"
                    v-model.trim="newDeployment.description"
                    rows="3"
                    :class="[
                        'max-h-32 resize-y mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                        { 'border-red-500': v$.description.$error },
                    ]"
                    aria-invalid="true"
                    aria-describedby="description-error"
                ></textarea>
                <p
                    v-if="v$.description.$error"
                    id="description-error"
                    class="text-xs text-red-500 mt-1"
                >
                    {{ v$.description.$errors[0]?.$message }}
                </p>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                :disabled="v$.$invalid"
                class="mt-4 w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                Add Deployment
            </button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from "vue";
import useVuelidate from "@vuelidate/core";
import { required, helpers } from "@vuelidate/validators";

// Reactive state
const newDeployment = ref({
    uid: "",
    name: "",
    latitude: 0,
    longitude: 0,
    description: "",
});

const props = defineProps<{
    layout: any[];
    info: string;
}>();

const localLayout = ref<any[]>(props.layout);
const localInfo = ref(props.info);

watch(
    () => props.layout,
    (newLayout) => {
        localLayout.value = JSON.parse(JSON.stringify(newLayout)); // deep copy to avoid mutation issues
    },
    { immediate: true, deep: true },
);

watch(
    () => props.info,
    (newInfo) => {
        console.log("AddDeployment got new info:", newInfo);
        localInfo.value = newInfo;
    },
    { immediate: true },
);

// Validation rules
const isValidNumber = (value) =>
    !isNaN(value) && value !== null && value !== "";

const rules = {
    name: { required },
    latitude: {
        required,
        isValidNumber: helpers.withMessage(
            "Latitude must be a valid number",
            isValidNumber,
        ),
        min: helpers.withMessage(
            "Latitude must be between -90 and 90",
            (value) => isValidNumber(value) && value >= -90 && value <= 90,
        ),
    },
    longitude: {
        required,
        isValidNumber: helpers.withMessage(
            "Longitude must be a valid number",
            isValidNumber,
        ),
        min: helpers.withMessage(
            "Longitude must be between -180 and 180",
            (value) => isValidNumber(value) && value >= -180 && value <= 180,
        ),
    },
    description: {},
};

const v$ = useVuelidate(rules, newDeployment);

// State for success/error messages
const successMessage = ref("");
const errorMessage = ref("");

// Submit deployment and notify the globe component
const submitDeployment = async () => {
    v$.value.$touch(); // Validate all fields

    if (v$.value.$invalid) {
        console.error("Validation failed:", v$.value.$errors);
        return;
    }

    try {
        console.log("Submitting deployment with layout:", localLayout.value);
        const clonedLayout = JSON.parse(JSON.stringify(localLayout.value));

        console.log("info:", localInfo.value);

        const response = await fetch(
            `${import.meta.env.BASE_URL}api/deployments`,
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    name: newDeployment.value.name,
                    latitude: parseFloat(newDeployment.value.latitude),
                    longitude: parseFloat(newDeployment.value.longitude),
                    description: props.info, // this is the "info" from the dashboard
                    info: localInfo.value,
                    layout: clonedLayout,
                }),
            },
        );

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || "Failed to add deployment.");
        }

        // Clear the form
        newDeployment.value = {
            name: "",
            latitude: 0,
            longitude: 0,
            description: "",
        };

        // Show success message
        successMessage.value = "Deployment added successfully!";
        errorMessage.value = "";

        setTimeout(() => {
            window.location.reload();
        }, 1000); // slight delay so users see the success message
    } catch (error) {
        // Handle errors
        errorMessage.value = error.message || "An unexpected error occurred.";
        console.error("Error submitting deployment:", error.message);
        successMessage.value = "";
    }
};
</script>

<style scoped></style>
