<script lang="ts" setup>
import { ref, computed, onMounted, watch } from "vue";
import axios from "axios";

import { useTranslation } from "../composables/useTranslation";

const { t } = useTranslation();

const props = defineProps<{
    editMode: boolean;
    dashboardData: { datasetIds: number[] }[];
}>();

const emit = defineEmits<{
    (e: "datasetDeleted", value: { datasetIds: number[] }[]): void;
}>();

// Interface representing a single dataset object
interface Dataset {
    id: number;
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
const datasetsToLoad = ref(props.dashboardData[0]?.datasetIds ?? []);

watch(
    () => props.dashboardData.map((d) => [...d.datasetIds]), // watch for datasetIds change
    async () => {
        datasetsToLoad.value = props.dashboardData[0]?.datasetIds ?? [];
        await fetchDatasets();
    },
    { deep: true },
);

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
        datasets.value = datasets.value.filter((ds) =>
            datasetsToLoad.value.includes(ds.id),
        );
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

const deleteDataset = (datasetToDelete: Dataset) => {
    const current = props.dashboardData[0]?.datasetIds || [];
    const updated = current.filter((id) => id !== datasetToDelete.id);

    emit("datasetDeleted", [{ datasetIds: updated }]);
};

// Fetch datasets when the component is mounted
onMounted(() => {
    fetchDatasets();
});
</script>

<template>
    <div class="flex flex-col h-full w-full p-4 overflow-hidden">
        <!-- Top controls -->
        <div class="mb-4">
            <label
                for="dataset-select"
                class="mr-2 font-medium text-gray-700"
                >{{ t.selectDataset }}</label
            >
            <select
                id="dataset-select"
                v-model="selectedDataset"
                class="border border-gray-300 rounded-md px-3 py-2 text-gray-700"
            >
                <option value="">{{ t.showAllDatasets }}</option>
                <option
                    v-for="option in datasets"
                    :key="option.dataset_name"
                    :value="option.dataset_name"
                >
                    {{ option.dataset_name }}
                </option>
            </select>
        </div>

        <!-- Scrollable Table Container -->
        <div
            class="flex-1 min-h-0 overflow-y-auto overflow-x-auto border rounded"
        >
            <table class="w-full table-fixed text-sm">
                <thead
                    class="bg-gray-100 text-gray-700 uppercase font-medium sticky top-0"
                >
                    <tr>
                        <th
                            class="w-4/10 border border-gray-200 px-3 py-2 text-left truncate"
                        >
                            {{ t.name }}
                        </th>
                        <th
                            class="w-5/10 border border-gray-200 px-3 py-2 text-left truncate"
                        >
                            {{ t.description }}
                        </th>
                        <th
                            v-if="editMode"
                            class="w-1/10 border border-gray-200 px-3 py-2 text-left truncate"
                        >
                            X
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
                            class="border border-gray-200 px-3 py-2 truncate"
                            :title="dataset.dataset_name"
                        >
                            {{ dataset.dataset_name }}
                        </td>
                        <td
                            class="border border-gray-200 px-3 py-2 truncate"
                            :title="dataset.metadata"
                        >
                            {{ dataset.metadata }}
                        </td>
                        <td
                            v-if="editMode"
                            class="border border-gray-200 px-3 py-2 truncate cursor-pointer"
                        >
                            <span
                                class="pi pi-trash"
                                @click="deleteDataset(dataset)"
                            ></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination controls -->
        <div
            class="mt-4 flex flex-col sm:flex-row justify-between items-center gap-2"
        >
            <button
                :disabled="!pagination.prev_page_url"
                class="px-3 py-2 border border-gray-300 rounded-md w-full sm:w-auto"
                @click="
                    pagination.prev_page_url &&
                    fetchDatasets(pagination.prev_page_url)
                "
            >
                {{ t.previous }}
            </button>
            <span class="text-sm text-gray-600 font-medium">
                {{ t.page }} {{ pagination.current_page }} {{ t.of }}
                {{ pagination.last_page }}
            </span>
            <button
                :disabled="!pagination.next_page_url"
                class="px-3 py-2 border border-gray-300 rounded-md w-full sm:w-auto"
                @click="
                    pagination.next_page_url &&
                    fetchDatasets(pagination.next_page_url)
                "
            >
                {{ t.next }}
            </button>
        </div>
    </div>
</template>

<style scoped></style>
