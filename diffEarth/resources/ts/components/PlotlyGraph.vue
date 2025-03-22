<script setup lang="ts">
import { computed, ref } from "vue";
import { VuePlotly } from "@clalarco/vue3-plotly";
import { Datatrace, addDataTrace } from "../datatrace.js";

// toggle elements when clicked
const filter = ref(false);

const importDataTog = ref(false);

const showCategory = ref(false);

const graphType = ref("markers");

const catLoad = ref(false);

const datasetLoad = ref(false);

const traceName = ref("");

const dotSize = ref(5);

//toggle filter element
function toggleFilter() {
    filter.value = !filter.value;
}

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
}

// Chart data management:
// Import data, select dataset, 1, User clicks and selects Dataset
async function importData() {
    datasetLoad.value = true;

    toggleDataImport();
    const datasets = await fetchDatasets();
    selectDataset.value = datasets.map((item) => ({
        id: item.id,
        dataset_name: item.dataset_name,
    }));

    datasetLoad.value = false;
}

// Fetch categories from selected dataset, 2, User selects the categroy from dataset
async function getCategories(id: number) {
    // set div visibility
    showCategory.value = true;
    // Set category lading to true
    catLoad.value = true;

    const categories = await fetchCategories(id);
    selectCategory.value = categories.map((item) => ({
        id: item.id,
        category: item.column_name,
    }));

    catLoad.value = false;
}

// Fetch category data and timestamps, 3, data from the selected category is fetched
async function getCategoryData(id: number) {
    toggleDataImport();

    const data = await fetchPlots(id);
    const xAxis = [];
    const yAxis = [];

    data.forEach((item) => {
        xAxis.push(item.timestamp);
        yAxis.push(item.data);
    });

    // chartData.value = [singleTrack];
    const trace: Datatrace = addDataTrace(
        xAxis,
        yAxis,
        graphType.value,
        traceName.value,
        {
            size: dotSize.value,
        },
    );
    chartData.value = [...chartData.value, trace];

    // Reset Trace Name
    resetTraceName();
}

async function fetchPlots(id: number) {
    return fetch(`/api/columns/${id}/data+stamp`)
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
    return fetch("/api/dataset")
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
    return fetch(`/api/dataset/${id}/columns`)
        .then((response) => response.json())
        .then((data) => {
            return data;
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

// Chart array variables
const selectDataset = ref([]);
const selectCategory = ref([]);

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
}));
const config = {
    responsive: true,
};

const chartData = ref(<Datatrace[]>[]);
</script>

<template>
    <div
        class="flex flex-col justify-center rounded m-3 shadow shrink grow max-w-250"
    >
        <section class="shadow rounded flex flex-col w-full">
            <nav
                class="rounded-t-l flex flex-wrap flex-auto justify-between bg-gray-200"
            >
                <div
                    id="chartTitle"
                    class="border-b-gray-400 bg-white rounded shadow m-1 flex flex-row items-center"
                >
                    <i class="pi pi-pencil m-1"></i>
                    <input
                        id="graphTitle"
                        v-model="graphTitle"
                        type="text"
                        name="graphTitle"
                        class="m-1 w-25"
                        aria-label="Rename Graph Title input"
                        placeholder="Graph Title ..."
                    />
                </div>
                <div
                    id="xTitle"
                    class="border-b-gray-400 bg-white rounded shadow m-1 flex flex-row items-center"
                >
                    <i class="pi pi-pencil m-1"></i>
                    <input
                        id="xTitle"
                        v-model="xTitle"
                        type="text"
                        name="X-Axis Title"
                        class="m-1 w-25"
                        aria-label="Rename x-axis input"
                        placeholder="X-Axis Title"
                    />
                </div>
                <div
                    id="yTitle"
                    class="border-b-gray-400 bg-white rounded shadow m-1 flex flex-row items-center"
                >
                    <i class="pi pi-pencil m-1"></i>
                    <input
                        id="graphTitle"
                        v-model="yTitle"
                        type="text"
                        name="Y-Axis Title"
                        class="m-1 w-25"
                        aria-label="Rename y-axis Title input"
                        placeholder="Y-Axis Title"
                    />
                </div>
                <div
                    class="border-b-gray-400 rounded shadow m-1 bg-white flex flex-row items-center"
                >
                    <button
                        class="m-1 font-sans"
                        aria-label="filter by date"
                        @click="toggleFilter"
                    >
                        <i class="pi pi-filter"></i>
                    </button>
                    <i v-if="!filter" class="pi pi-angle-down"></i>
                    <i v-if="filter" class="pi pi-angle-double-up"></i>
                </div>
                <div
                    class="border-b-gray-400 rounded shadow m-1 bg-white flex flex-row items-center"
                >
                    <i class="pi pi-cog m-3"></i>
                    <select
                        id="graphType"
                        v-model="graphType"
                        name="graphType"
                        class="m-1"
                        aria-label="Select Graph Type"
                    >
                        <option value="scatter" class="" aria-label="Scatter">
                            Scatter
                        </option>
                        <option value="bar" class="" aria-label="Bar">
                            Bar
                        </option>
                    </select>
                </div>
                <button
                    class="m-1 bg-white rounded shadow"
                    aria-label="Import data from database "
                    @click="importData"
                >
                    <i class="pi pi-database p-1"></i>
                    <i class="pi pi-angle-right p-1"></i>
                </button>
            </nav>
            <div
                v-if="filter"
                id="filter"
                class="border-b-gray-400 rounded m-3 shadow"
            >
                <form class="flex justify-center items-center m-1">
                    <div class="mx-3">
                        <label for="start">Start date: </label>
                        <input id="start" type="date" name="start" />
                    </div>
                    <div class="mx-3">
                        <label for="end">End date: </label>
                        <input id="end" type="date" name="end" />
                    </div>
                    <button class="pi pi-search">Search</button>
                </form>
            </div>
            <div class="flex justify-center rounded shrink w-full">
                <VuePlotly
                    :data="chartData"
                    :layout="layout"
                    :config="config"
                    class="flex shrink w-full"
                ></VuePlotly>
            </div>
        </section>

        <transition name="modal">
            <div
                v-if="importDataTog"
                class="fixed inset-0 bg-gray-800/50 flex flex-row items-center justify-center rounded"
            >
                <div class="rounded bg-gray-50">
                    <div id="close" class="flex items-center justify-end m-1">
                        <button
                            class="border rounded bg-red-400"
                            aria-label="Close"
                            @click="toggleDataImport"
                        >
                            <i class="pi pi-times text-lg p-1"></i>
                        </button>
                    </div>
                    <div
                        v-if="importDataTog"
                        id="createNewTrace"
                        class="flex flex-row items-center justify-center m-1"
                    >
                        <input
                            id="nameNewTrace"
                            v-model="traceName"
                            class="mx-1 border-gray-600 border shadow rounded-xl p-1"
                            type="text"
                            name="New trace name... "
                            placeholder="New trace name..."
                            aria-label="Add trace name"
                        />
                        <label for="plotSize" class="mx-1">Plot size: </label>
                        <input
                            v-model="dotSize"
                            class="mx-1 border-gray-600 border shadow rounded-xl p-1"
                            type="number"
                            id="plotSize"
                            placeholder="5"
                            aria-label="Plot size"
                        />
                    </div>
                    <div id="dataset" class="p-4 bg-gray-50">
                        <div
                            v-if="datasetLoad"
                            class="flex flex-row items-center justify-center m-3 overflow-y-scroll max-h-75"
                        >
                            <!--                            Provided by Fergus, taken from https://git.cardiff.ac.uk/c22026756/68b-cardiff-earth/-/merge_requests/7#e05d7d656aa21d00d562fe408032f60e87d33639-->
                            <svg
                                class="animate-spin h-5 w-5 inline-block mr-2"
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
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                                ></path>
                            </svg>
                        </div>
                        <ul class="space-y-2">
                            <li
                                v-for="dataset in selectDataset"
                                :key="dataset.id"
                            >
                                <button
                                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 transition"
                                    aria-label="Select dataset"
                                    @click="getCategories(dataset.id)"
                                >
                                    {{ dataset.dataset_name }}
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div
                        v-if="showCategory"
                        id="category"
                        class="mt-4 p-4 rounded-lg bg-gray-50 overflow-y-scroll max-h-75"
                    >
                        <div
                            v-if="catLoad"
                            class="flex flex-row items-center justify-center m-3"
                        >
                            <svg
                                class="animate-spin h-5 w-5 inline-block mr-2"
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
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                                ></path>
                            </svg>
                        </div>
                        <ul class="space-y-2">
                            <li
                                v-for="category in selectCategory"
                                :key="category.id"
                            >
                                <button
                                    class="w-full px-4 py-2 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 transition"
                                    aria-label="Select category"
                                    @click="getCategoryData(category.id)"
                                >
                                    {{ category.category }}
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div
                        id="saveClose"
                        class="flex items-center justify-center m-1"
                    >
                        <button
                            class="border rounded bg-gray-300-400 p-1"
                            aria-label="Close"
                            @click="resetChart"
                        >
                            <i class="pi pi-undo text-lg m-1"></i>
                            Reset Chart
                        </button>
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
</style>
