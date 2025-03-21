<script lang="ts" setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios";

import { useTranslation } from "../composables/useTranslation";

const { t } = useTranslation();
// Interface representing a single dataset object
interface Dataset {
    dataset_name: string;
    updated_at: string;
    created_at: string;
    metadata: string;
}

// Interface representing pagination details
interface Pagination {
    current_page: number;
    last_page: number;
    next_page_url: string | null;
    prev_page_url: string | null;
}

const datasets = ref<Dataset[]>([]);

const pagination = ref<Pagination>({
    current_page: 1,
    last_page: 1,
    next_page_url: null,
    prev_page_url: null,
});

// Loading status
const loading = ref(true);

// Function to fetch datasets from the API
const fetchDatasets = async (url: string = "/api/datasets") => {
    loading.value = true;
    try {
        // Make GET request to fetch datasets
        const response = await axios.get(url);
        datasets.value = response.data.data;
        pagination.value = {
            current_page: response.data.current_page, // Set current page
            last_page: response.data.last_page, // Set total pages
            next_page_url: response.data.next_page_url, // Set next page URL
            prev_page_url: response.data.prev_page_url, // Set previous page URL
        };
    } catch (error) {
        console.error("Error fetching datasets:", error);
    } finally {
        loading.value = false;
    }
};

const selectedDataset = ref("");

const MAX_ENTRIES = 50;

// Computed property to filter or truncate datasets
const filteredOrTruncatedDatasets = computed(() => {
    if (!selectedDataset.value) {
        // Return truncated datasets if no dataset is selected
        return datasets.value.slice(0, MAX_ENTRIES);
    }
    // Return filtered datasets by selected name
    return datasets.value.filter(
        (ds) => ds.dataset_name === selectedDataset.value,
    );
});

// Function to handle dataset selection change
const onDatasetChange = () => {
    // Log the selected dataset
    console.log("Selected Dataset:", selectedDataset.value);
};

// Fetch datasets when the component is mounted
onMounted(() => {
    fetchDatasets();
});
</script>

<template>
    <div class="p-4 transform scale-88 origin-top-left">
        <div v-if="loading" class="text-center py-4 text-gray-600 font-medium">
            {{ t.loading }}
        </div>
        <div v-else>
            <!-- Dropdown for Dataset Selection -->
            <div class="mb-4">
                <label
                    for="dataset-select"
                    class="block mb-1 text-gray-700 font-medium"
                >
                    {{ t.selectDataset }}
                </label>
                <select
                    id="dataset-select"
                    v-model="selectedDataset"
                    class="block w-full md:max-w-sm border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring focus:ring-blue-400 shadow-sm transition"
                    @change="onDatasetChange"
                >
                    <!-- Default Option -->
                    <option value="" selected>{{ t.showAllDatasets }}</option>
                    <!-- Loop through Dataset Options -->
                    <option
                        v-for="option in datasets"
                        :key="option.dataset_name"
                        :value="option.dataset_name"
                    >
                        {{ option.dataset_name }}
                    </option>
                </select>
            </div>

            <!-- Responsive Data Table -->
            <div class="overflow-x-auto">
                <table>
                    <thead
                        class="bg-gray-100 text-gray-700 text-xs uppercase font-medium"
                    >
                        <tr>
                            <th
                                class="border border-gray-200 px-3 py-2 text-left max-w-[150px] truncate"
                            >
                                {{ t.name }}
                            </th>
                            <th
                                class="border border-gray-200 px-3 py-2 text-left max-w-[250px] truncate"
                            >
                                {{ t.description }}
                            </th>
                            <th
                                class="border border-gray-200 px-3 py-2 text-center w-[120px]"
                            >
                                {{ t.lastUpdated }}
                            </th>
                            <th
                                class="border border-gray-200 px-3 py-2 text-center w-[120px]"
                            >
                                {{ t.createdAt }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="dataset in filteredOrTruncatedDatasets"
                            :key="dataset.dataset_name"
                            class="hover:bg-gray-50 text-gray-600"
                        >
                            <td
                                class="border border-gray-200 px-3 py-2 text-left truncate max-w-[120px]"
                                :title="dataset.dataset_name"
                            >
                                {{ dataset.dataset_name }}
                            </td>
                            <td
                                class="border border-gray-200 px-3 py-2 text-left truncate max-w-[250px]"
                                :title="dataset.metadata"
                            >
                                {{ dataset.metadata }}
                            </td>
                            <td
                                class="border border-gray-200 px-3 py-2 text-center"
                            >
                                {{
                                    new Date(
                                        dataset.updated_at,
                                    ).toLocaleDateString()
                                }}
                            </td>
                            <td
                                class="border border-gray-200 px-3 py-2 text-center"
                            >
                                {{
                                    new Date(
                                        dataset.created_at,
                                    ).toLocaleDateString()
                                }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="mt-4 flex justify-between items-center">
                <button
                    :disabled="!pagination.prev_page_url"
                    class="px-3 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-blue-50 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
                    @click="
                        pagination.prev_page_url &&
                        fetchDatasets(pagination.prev_page_url)
                    "
                >
                    {{ t.previous }}
                </button>

                <span class="text-gray-600 text-sm font-medium">
                    {{ t.page }} {{ pagination.current_page }} {{ t.of }}
                    {{ pagination.last_page }}
                </span>

                <button
                    :disabled="!pagination.next_page_url"
                    class="px-3 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-blue-50 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
                    @click="
                        pagination.next_page_url &&
                        fetchDatasets(pagination.next_page_url)
                    "
                >
                    {{ t.next }}
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
