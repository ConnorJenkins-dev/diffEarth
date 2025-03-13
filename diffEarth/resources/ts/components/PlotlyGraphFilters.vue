<script setup lang="ts">
import { ref } from "vue";
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
</script>

<template>
    <nav class="rounded-t-l flex flex-row overflow-x-auto">
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
                class="m-1 w-full"
                aria-label="Rename Graph Title input"
                placeholder=""
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
                class="m-1 w-full"
                aria-label="Rename x-axis input"
                placeholder=""
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
                class="m-1 w-full"
                aria-label="Rename y-axis Title input"
                placeholder=""
            />
        </div>
        <div
            class="border-b-gray-400 rounded shadow m-1 bg-white flex flex-row items-center"
            @click="toggleFilter"
        >
            <i class="pi pi-filter"></i>
            <i v-if="!filter" class="pi pi-angle-down"></i>
            <i v-if="filter" class="pi pi-angle-double-up"></i>
        </div>
        <div
            class="border-b-gray-400 rounded shadow m-1 bg-white flex flex-row items-center"
        >
            <i class="pi pi-cog" @click="$refs.graphTypeSelect.click()" />
            <select
                id="graphType"
                ref="graphTypeSelect"
                v-model="graphType"
                name="graphType"
                class="m-1 w-full"
            >
                <option value="scatter" class="" aria-label="Scatter">
                    Scatter
                </option>
                <option value="bar" class="" aria-label="Bar">Bar</option>
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
    <div v-if="filter" id="filter" class="border-b-gray-400 rounded m-3 shadow">
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
</template>

<style scoped></style>
