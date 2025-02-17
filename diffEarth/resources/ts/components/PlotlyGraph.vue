<script setup lang="ts">
import { computed, ref } from "vue";
import { VuePlotly } from "@clalarco/vue3-plotly";

// toggle elements when clicked
const filter = ref(false);

//toggle filter element
function toggleFilter() {
    filter.value = !filter.value;
}

const graphType = ref("scatter");

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

// Main graph data:
const data = computed(() => [
    {
        x: [1, 2, 3, 4, 5, 6],
        y: [10, 15, 13, 17, 10, 9],
        type: graphType.value,
        name: "TestTrace",
    },
    {
        x: [1, 2, 3, 4, 5, 9],
        y: [10, 15, 13, 17, 5, 12],
        type: graphType.value,
        name: "TestTrace2",
    },
]);
</script>

<template>
    <div class="flex justify-center rounded m-3 shadow shrink grow max-w-250">
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
                    <button class="m-1" aria-label="Import data from database">
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
                    :data="data"
                    :layout="layout"
                    :config="config"
                    class="flex shrink w-full"
                ></VuePlotly>
            </div>
        </section>
    </div>
</template>

<style scoped></style>
