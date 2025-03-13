<script setup lang="ts">
import FileUpload from "./FileUpload.vue";
import DatasetView from "./DatasetView.vue";
import { ref, watch } from "vue";

const props = defineProps<{
    editMode: boolean;
    dashboardData: { datasetIds: number[] }[];
}>();

const emit = defineEmits<{
    (e: "updateDashboardData", value: { datasetIds: number[] }[]): void;
    (e: "fileProcessing", value: boolean): void;
}>();

const componentData = ref(props.dashboardData);

watch(
    () => props.dashboardData,
    (newData) => {
        componentData.value = newData;
        console.log(
            "Dashboard Table updated: new dashboard data: ",
            componentData.value,
        );
    },
);

const addDataset = (datasetId: number) => {
    const updatedData = [
        {
            datasetIds: [
                ...(componentData.value[0]?.datasetIds || []),
                datasetId,
            ],
        },
    ];
    componentData.value = updatedData;
    emit("updateDashboardData", updatedData);
};

const csvProcessed = (datasetId: number) => {
    addDataset(datasetId);
};

const datasetDeleted = (newDatasets: { datasetIds: number[] }[]) => {
    componentData.value = newDatasets;
    emit("updateDashboardData", newDatasets);
};
</script>

<template>
    <div class="flex flex-col items-center h-full w-full min-h-0">
        <FileUpload
            v-if="editMode"
            @csvProcessed="csvProcessed"
            @fileProcessing="$emit('fileProcessing', $event)"
        />
        <DatasetView
            :editMode="editMode"
            :dashboardData="dashboardData"
            @datasetDeleted="datasetDeleted"
        />
    </div>
</template>

<style scoped></style>
