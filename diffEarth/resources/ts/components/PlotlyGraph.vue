<script setup lang="ts">
import { computed, nextTick, onMounted, ref, watch } from "vue";
import { VuePlotly } from "@clalarco/vue3-plotly";
import PlotlyGraphFilters from "./PlotlyGraphFilters.vue";
import { Datatrace, addDataTrace } from "../datatrace.js";
import { useTranslation } from "../composables/useTranslation";

const props = defineProps<{
    editMode: boolean;
    dashboardData: { columnId: number; traceName: string }[];
}>();

const emit = defineEmits<{
    (
        e: "updateDashboardData",
        value: { columnId: number; traceName: string }[],
    ): void;
}>();

const filter = ref(false); // Date filter

const { t } = useTranslation();

const importDataTog = ref(false);

const showCategory = ref(false);

const graphType = ref("markers");

const catLoading = ref(false);

const datasetLoading = ref(false);

const traceName = ref("");

const dotSize = ref(5);

const graphData = ref(props.dashboardData ?? []);

watch(
    () => props.dashboardData,
    (newData) => {
        graphData.value = newData;
    },
);

// When graphData updates
watch(
    graphData,
    (newVal) => {
        emit("updateDashboardData", newVal);
    },
    { deep: true },
);

//toggle filter element
const toggleFilter = () => {
    filter.value = !filter.value;
};

// Toggle to stop dragging and clicking events while editing dashboard layout
const isInteractive = ref(true);
watch(
    () => props.editMode,
    (isEditing) => {
        isInteractive.value = !isEditing; // Interactive = NOT editing
    },
);

function toggleDataImport() {
    importDataTog.value = !importDataTog.value;
}

function resetTraceName() {
    traceName.value = "";
}

function resetDotSize() {
    dotSize.value = 5;
}

// Reset variables to default. Clean work space.
function resetChart() {
    chartData.value = [];
    resetTraceName();
    resetDotSize();
    importDataTog.value = false;
}

// Chart data management:
// Import data, select dataset, 1, User clicks and selects Dataset
async function openImportModal() {
    datasetLoading.value = true;

    importDataTog.value = true;
    const datasets: { id: number; dataset_name: string }[] =
        await fetchDatasets();
    visibleDatasets.value = datasets.map((item) => ({
        id: item.id,
        dataset_name: item.dataset_name,
    }));

    datasetLoading.value = false;
}

function closeImportModal() {
    importDataTog.value = false;
    showCategory.value = false;
}

// Fetch categories from selected dataset, 2, User selects the categroy from dataset
async function addCategoriesToModal(id: number) {
    // set div visibility
    showCategory.value = true;
    // Set category lading to true
    catLoading.value = true;

    const categories: { id: number; column_name: string }[] =
        await fetchCategories(id);
    visibleCategories.value = categories.map((item) => ({
        id: item.id,
        column_name: item.column_name,
    }));

    catLoading.value = false;
}

// Fetch category data and timestamps, 3, data from the selected category is fetched
async function addTraceToGraph(
    givenColId: number,
    givenTraceName?: string,
    fromDatabase = false,
) {
    importDataTog.value = false; // close modal if open

    const data: { timestamp: any; data: any }[] = await fetchPlots(givenColId);
    const xAxis: any[] = [];
    const yAxis: any[] = [];

    data.forEach((item) => {
        xAxis.push(item.timestamp);
        yAxis.push(item.data);
    });

    if (givenTraceName == null && traceName.value == "") {
        givenTraceName = "Unnamed Trace";
    }

    const trace: Datatrace = addDataTrace(
        xAxis,
        yAxis,
        graphType.value,
        givenTraceName ?? traceName.value,
        {
            size: dotSize.value,
        },
    );
    chartData.value = [...chartData.value, trace];

    if (!fromDatabase) {
        // Only append to graphData if it's a user-added trace,
        // not when loading from database
        graphData.value = [
            ...graphData.value,
            {
                columnId: givenColId,
                traceName: givenTraceName ?? traceName.value,
            },
        ];
    }

    // Reset Trace Name
    resetTraceName();
}

async function fetchGraphData() {
    graphData.value.forEach(({ columnId, traceName }) => {
        addTraceToGraph(columnId, traceName, true);
    });
}

async function fetchPlots(id: number) {
    return fetch(`${import.meta.env.BASE_URL}api/columns/${id}/data+stamp`)
        .then((response) => response.json())
        .then((data) => {
            return data;
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

// Fetch list of all datasets
async function fetchDatasets() {
    return fetch(`${import.meta.env.BASE_URL}api/dataset`)
        .then((response) => response.json())
        .then((data) => {
            return data;
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

// Fetch list of all categories
async function fetchCategories(id: number) {
    return fetch(`${import.meta.env.BASE_URL}api/dataset/${id}/columns`)
        .then((response) => response.json())
        .then((data) => {
            return data;
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

function handleDatasetSelected(event: Event) {
    const target = event.target as HTMLSelectElement;
    const selectedDataset = parseInt(target.value) ?? 0;
    if (selectedDataset) {
        addCategoriesToModal(selectedDataset);
    }
}

function handleCategorySelected(event: Event) {
    const target = event.target as HTMLSelectElement;
    const selectedCategory = parseInt(target.value) ?? 0;
    if (selectedCategory) {
        addTraceToGraph(selectedCategory);
    }
    showCategory.value = false;
}
// Chart array variables
const visibleDatasets = ref<{ id: number; dataset_name: string }[]>([]);
const visibleCategories = ref(<{ id: number; column_name: string }[]>[]);

// Plotly template taken from: https://plotly.com/javascript/line-charts/

// Graph titles:
const graphTitle = ref("");
const xTitle = ref("");
const yTitle = ref("");

// Configuration adapted from: https://plotly.com/javascript/configuration-options/
// Set titles
const layout = computed(() => ({
    title: { text: graphTitle.value },
    xaxis: { title: { text: xTitle.value } },
    yaxis: { title: { text: yTitle.value } },
    mode: "markers",
    autosize: true,
    responsive: true,
    margin: {
        t: 50,
        l: 50,
        r: 30,
        b: 100,
    },
    dragMode: isInteractive.value ? "pan" : false,
    showlegend: true,
    modebar: {
        orientation: "h",
    },
}));
const config = computed(() => ({
    responsive: true,
    scrollZoom: isInteractive.value,
    editable: !isInteractive.value,
    displayModeBar: true,
    modeBarButtonsToRemove: ["lasso2d", "autoScale2d", "toImage"],
    displayLogo: false,
}));

const chartData = ref(<Datatrace[]>[]);

onMounted(async () => {
    await fetchGraphData();
});
</script>

<template>
    <div class="h-full">
        <nav
            class="rounded-t-l flex flex-row overflow-x-auto bg-gray-200 rounded"
        >
            <!-- Date filter -->
            <div
                class="border-b-gray-400 rounded shadow m-1 bg-white flex flex-row items-center justify-center whitespace-nowrap"
                @click="toggleFilter"
            >
                <i class="pi pi-filter p-1" /> Filter by Date
                <i v-if="!filter" class="pi pi-angle-down p-1"></i>
                <i v-if="filter" class="pi pi-angle-double-up p-1"></i>
            </div>

            <!-- Import Data -->
            <div
                v-if="editMode"
                class="border-b-green-400 m-1 rounded shadow bg-white flex flex-row items-center whitespace-nowrap"
            >
                <button
                    class="p-1"
                    aria-label="Import data from database"
                    @click="openImportModal"
                >
                    <i class="pi pi-database p-1" /> Add Plot
                </button>
                <i class="pi pi-plus p-1" />
            </div>
        </nav>
        <div
            v-if="filter"
            id="filter"
            class="border-b-gray-400 rounded m-3 shadow"
        >
            <form class="flex justify-center items-center m-1">
                <div class="mx-3">
                    <label for="start">{{ t.startDate }}</label>
                    <input id="start" type="date" name="start" />
                </div>
                <div class="mx-3">
                    <label for="end">{{ t.endDate }}</label>
                    <input id="end" type="date" name="end" />
                </div>
                <button class="pi pi-search">{{ t.search }}</button>
            </form>
        </div>
        <VuePlotly
            :data="chartData"
            :layout="layout"
            :config="config"
            class="flex shrink w-full max-h-full h-full min-h-0 overflow-hidden"
        />
        <!-- Barrier to block drag events on graph while in edit mode -->
        <div
            v-if="!isInteractive"
            class="absolute inset-0 left-10 top-25 h-1/2 w-2/3 z-10 bg-transparent"
        />
        <transition name="modal" class="@container z-30">
            <div
                v-if="importDataTog"
                class="fixed inset-0 bg-gray-800/50 flex items-center justify-center rounded"
            >
                <div class="rounded bg-gray-50 flex flex-col h-6/8 p-2 w-3/4">
                    <!-- Top (fixed position) -->
                    <div class="flex items-center justify-between m-1">
                        <div class="flex items-center justify-center">
                            <button
                                class="border rounded bg-gray-300-400 p-1"
                                @click="resetChart"
                            >
                                <i class="pi pi-undo text-lg m-1"></i>
                                Reset Chart
                            </button>
                        </div>
                        <button
                            class="border rounded bg-red-400"
                            aria-label="Close"
                            @click="closeImportModal()"
                        >
                            <i class="pi pi-times text-lg p-1"></i>
                        </button>
                    </div>

                    <!-- Main form (grows to fill space and evenly space children) -->
                    <div class="flex flex-col justify-evenly flex-1 px-2 pb-2">
                        <!-- Name and size -->
                        <div
                            class="flex flex-wrap gap-2 w-full items-center justify-between"
                        >
                            <input
                                v-model="traceName"
                                class="mx-1 border-gray-600 border shadow rounded-xl p-1 min-w-0 flex-1 truncate"
                                :placeholder="t.newTraceName"
                            />
                            <label for="plotSize">{{ t.plotSize }}</label>
                            <input
                                v-model="dotSize"
                                class="border-gray-600 border shadow rounded-xl p-1 min-w-0 flex-1 truncate max-w-16"
                                type="number"
                                id="plotSize"
                                placeholder="5"
                                :aria-label="t.plotSize"
                            />
                        </div>

                        <!-- Dropdowns -->
                        <div
                            class="flex flex-row gap-2 w-full items-center justify-between"
                        >
                            <!-- Dataset -->
                            <div
                                class="p-2 bg-gray-50 flex-1 min-w-0 max-w-[50%]"
                            >
                                <!-- Loader while dataset is loading -->
                                <div
                                    v-if="datasetLoading"
                                    class="flex flex-row items-center justify-center min-h-[38px]"
                                >
                                    <svg
                                        class="animate-spin h-5 w-5 text-gray-600"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            class="opacity-25"
                                            cx="12"
                                            cy="12"
                                            r="10"
                                            stroke="currentColor"
                                            stroke-width="4"
                                        />
                                        <path
                                            class="opacity-75"
                                            fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                                        />
                                    </svg>
                                </div>

                                <!-- Dropdown shown only when not loading -->
                                <select
                                    v-else
                                    @change="handleDatasetSelected($event)"
                                    class="block w-full min-w-0 truncate bg-blue-600 text-white border border-gray-300 rounded px-2 py-1 shadow cursor-pointer"
                                >
                                    <option value="" selected disabled>
                                        Dataset
                                    </option>
                                    <option
                                        v-for="dataset in visibleDatasets"
                                        :key="dataset.id"
                                        :value="dataset.id"
                                    >
                                        {{ dataset.dataset_name }}
                                    </option>
                                </select>
                            </div>

                            <!-- Category -->
                            <div
                                v-if="showCategory"
                                class="p-2 bg-gray-50 flex-1 min-w-0 max-w-[50%]"
                            >
                                <!-- Loader while category is loading -->
                                <div
                                    v-if="catLoading"
                                    class="flex flex-row items-center justify-center min-h-[38px]"
                                >
                                    <svg
                                        class="animate-spin h-5 w-5 text-gray-600"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            class="opacity-25"
                                            cx="12"
                                            cy="12"
                                            r="10"
                                            stroke="currentColor"
                                            stroke-width="4"
                                        />
                                        <path
                                            class="opacity-75"
                                            fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                                        />
                                    </svg>
                                </div>

                                <!-- Dropdown shown only when not loading -->
                                <select
                                    v-else
                                    @change="handleCategorySelected($event)"
                                    class="block w-full min-w-0 truncate bg-green-600 text-white border border-gray-300 rounded px-2 py-1 shadow cursor-pointer"
                                >
                                    <option value="" selected disabled>
                                        Column
                                    </option>
                                    <option
                                        v-for="category in visibleCategories"
                                        :key="category.id"
                                        :value="category.id"
                                    >
                                        {{ category.column_name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}
.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
.modal-enter-to,
.modal-leave-from {
    opacity: 1;
}
.vue-plotly >>> .modebar {
    position: absolute !important;
    top: 0px !important;
    left: 50% !important;
    transform: translateX(-50%);
    display: flex !important;
    flex-direction: row !important;
    background: rgba(
        255,
        255,
        255,
        0.8
    ) !important; /* Optional: Add background for visibility */
    padding: 5px;
    border-radius: 5px;
    z-index: 10;
}
</style>
