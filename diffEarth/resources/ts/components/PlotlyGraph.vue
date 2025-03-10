<script setup lang="ts">
import { computed, ref } from "vue";
import { VuePlotly } from "@clalarco/vue3-plotly";
import { Datatrace, addDataTrace } from "../datatrace.js";

// toggle elements when clicked
const filter = ref(false);

const importDataTog = ref(false);

const datasetTog = ref(false);

const showCategory = ref(false);

const graphType = ref("scatter");

const catLoad = ref(false);

const datasetLoad = ref(false);

//toggle filter element
function toggleFilter() {
    filter.value = !filter.value;
}

function toggleDataset() {
    datasetTog.value = !datasetTog.value;
}

function toggleDataImport() {
    importDataTog.value = !importDataTog.value;
}

// Chart data management:
// Import data, select dataset
async function importData() {
    datasetLoad.value = true;

    importDataTog.value = !importDataTog.value;
    const datasets = await fetchDatasets();
    selectDataset.value = datasets.map((item) => ({
        id: item.id,
        dataset_name: item.dataset_name,
    }));

    datasetLoad.value = false;
}

// Fetch categories from selected dataset
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

// Fetch category data and timestamps
async function getCategoryData(id: number) {
    // reset chartData to be empty
    chartData.value = [];

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
        "MyChart",
    );
    chartData.value = [trace];
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
                <div
                    class="border-b-green-400 m-1 rounded shadow bg-white flex flex-row items-center"
                >
                    <button
                        class="m-1"
                        aria-label="Import data from database"
                        @click="importData"
                    >
                        <i class="pi pi-database"></i>
                    </button>
                    <i class="pi pi-angle-right"></i>
                </div>
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
                            <i class="pi pi-times"></i>
                        </button>
                    </div>
                    <div id="dataset" class="p-4 shadow-md bg-gray-50">
                        <div
                            v-if="datasetLoad"
                            class="flex flex-row items-center justify-center m-3"
                        >
                            <p>Loading...</p>
                            <br />
                            <i class="pi pi-hourglass"></i>
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
                        class="mt-4 p-4 rounded-lg bg-gray-50"
                    >
                        <div
                            v-if="catLoad"
                            class="flex flex-row items-center justify-center m-3"
                        >
                            <p>Loading...</p>
                            <br />
                            <i class="pi pi-hourglass"></i>
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
