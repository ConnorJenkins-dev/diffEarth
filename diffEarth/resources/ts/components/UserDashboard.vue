<script setup lang="ts">
import Header from "./Header.vue";
import PlotlyGraph from "./PlotlyGraph.vue";
import { useTranslation } from "../composables/useTranslation";
const { t } = useTranslation();
import DashboardInfo from "./DashboardInfo.vue";
import DashboardTable from "./DashboardTable.vue";
import { GridLayout, GridItem } from "grid-layout-plus";
import AddDeployment from "./AddDeployment.vue";
import { onMounted, ref, computed, nextTick, watch } from "vue";
import { onBeforeRouteLeave } from "vue-router";
import Weather from "./Weather.vue";

const unsaved = ref(false);
const processingUpload = ref(false);

onBeforeRouteLeave((to, from, next) => {
    if (processingUpload.value) {
        const leave = window.confirm(
            "A file is still being processed. It will NOT be saved. Are you sure you want to leave?",
        );
        leave ? next() : next(false);
        return;
    }

    if (unsaved.value) {
        const leave = window.confirm(
            "Your layout has not been saved. Are you sure you want to leave?",
        );
        leave ? next() : next(false);
        return;
    }

    next();
});

watch(processingUpload, (isProcessing) => {
    if (isProcessing) {
        window.addEventListener("beforeunload", handleBeforeUnload);
    } else {
        if (!unsaved.value) {
            window.removeEventListener("beforeunload", handleBeforeUnload);
        }
    }
});

const handleBeforeUnload = (event: BeforeUnloadEvent) => {
    event.preventDefault();
};

// Layout of components on grid
const layout = ref<
    {
        x: number;
        y: number;
        w: number;
        h: number;
        i: string;
        itemData:
            | { datasetIds: number[] }[]
            | { columnId: number; traceName: string }[]
            | null;
    }[]
>([{ x: 0, y: 0, w: 1, h: 1, i: "null", itemData: null }]);
const initialLayout = ref(layout);
const info = ref("");
const initialInfo = ref("");
const showModal = ref(false);

const fetchLayout = async () => {
    try {
        const response = await fetch(
            `${import.meta.env.BASE_URL}api/deployments/in-progress`,
        );
        if (!response.ok) {
            return;
        }
        const data = await response.json();
        layout.value = [...data.layout];
        initialLayout.value = data.layout;
        info.value = data.info;
        initialInfo.value = data.info;
    } catch (error) {
        console.error(error);
        return;
    }
    await nextTick(() => {
        handleResize();
    });
};

watch(
    layout,
    (newLayout) => {
        console.log("Layout updated:", newLayout);
    },
    { deep: true },
);

const totalColumns = 4;
const totalRows = 8;

// Compute all occupied grid positions
const occupiedPositions = computed(() => {
    const occupied = new Set();

    layout.value.forEach((item) => {
        for (let dx = 0; dx < item.w; dx++) {
            for (let dy = 0; dy < item.h; dy++) {
                occupied.add(`${item.x + dx}-${item.y + dy}`);
            }
        }
    });

    handleResize();

    return occupied;
});

// Compute empty slots dynamically
const emptySlots = computed(() => {
    const slots = [];

    for (let y = 0; y < totalRows; y++) {
        for (let x = 0; x < totalColumns; x++) {
            const positionKey = `${x}-${y}`;

            // Only add as empty if the slot is NOT occupied
            if (!occupiedPositions.value.has(positionKey)) {
                slots.push({ x, y, w: 1, h: 1, i: `empty_${x}_${y}` });
            }
        }
    }

    return slots;
});

const availableComponents = ref([
    { name: "graph", type: "graph" },
    { name: "table", type: "table" },
]);

const handleSelectChange = (event: Event, x: number, y: number) => {
    const target = event.target as HTMLSelectElement;
    const selectedType = target.value;
    if (selectedType) {
        addComponent(x, y, selectedType);
    }
    target.value = ""; // Reset dropdown to default after selection
};

const getEmptyData = (type: string): any[] => {
    if (type.startsWith("graph")) {
        return [{ columnId: 0, traceName: "" }];
    }
    return [{ datasetIds: [] }];
};

// Function to add a new component at a clicked empty slot
const addComponent = (x: number, y: number, type: string) => {
    const newIndex = layout.value.length;

    // New component data
    const newComponent = {
        x: x,
        y: y,
        w: getMinDimensions(type).width,
        h: getMinDimensions(type).height,
        i: `${type}_${newIndex}`,
        itemData: getEmptyData(type),
    };

    // Boundary Check: Prevent going outside the grid**
    if (
        newComponent.x + newComponent.w > totalColumns ||
        newComponent.y + newComponent.h > totalRows
    ) {
        console.warn("Component exceeds grid boundaries!");
        return;
    }

    // Overlap Check: Prevent placing over existing components**
    layout.value.some((existing) => {
        for (let dx = 0; dx < newComponent.w; dx++) {
            for (let dy = 0; dy < newComponent.h; dy++) {
                if (
                    x + dx >= existing.x &&
                    x + dx < existing.x + existing.w &&
                    y + dy >= existing.y &&
                    y + dy < existing.y + existing.h
                ) {
                    console.warn("Not enough space for new component!");
                    return; // Overlapping detected
                }
            }
        }
    });

    // Add new component to layout
    layout.value.push(newComponent);
    nextTick(() => {
        handleResize();
    });
};

// Function to delete a component by its ID
const deleteComponent = (componentId: string) => {
    layout.value = layout.value.filter((item) => item.i !== componentId);
};

const getComponent = (name: string) => {
    if (name.startsWith("graph")) {
        return PlotlyGraph;
    } else if (name.startsWith("table")) {
        return DashboardTable;
    } else {
        return null;
    }
};

const getMinDimensions = (name: string) => {
    if (name.startsWith("graph")) {
        return {
            height: 2,
            width: 2,
        };
    } else if (name.startsWith("table")) {
        return {
            height: 3,
            width: 2,
        };
    } else {
        return {
            height: 1,
            width: 1,
        };
    }
};

const handleResize = () => {
    window.dispatchEvent(new Event("resize"));
};

const editMode = ref(false);
const enableEditMode = () => {
    editMode.value = true;
    unsaved.value = true;
};

const cancelLayout = () => {
    editMode.value = false;
    layout.value = initialLayout.value;
    info.value = initialInfo.value;
    unsaved.value = false;
};

const publishLayout = () => {
    openModal();
};

const saveLayout = async (deploymentText: string) => {
    editMode.value = false;
    unsaved.value = false;
    info.value = deploymentText;
    try {
        const response = await fetch(
            `${import.meta.env.BASE_URL}api/deployments/in-progress`,
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    id: 1,
                    layout: layout.value,
                    info: deploymentText,
                }),
            },
        );

        if (response.ok) {
            console.log("Layout saved.");
        }
    } catch (error) {
        console.error(error);
    }
};

watch(editMode, (isEditing) => {
    if (isEditing) {
        window.addEventListener("beforeunload", handleBeforeUnload);
    } else {
        window.removeEventListener("beforeunload", handleBeforeUnload);
    }
});

onMounted(async () => {
    handleResize();
    await fetchLayout();
});

function openModal() {
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
}
</script>

<template>
    <Header />
    <body class="flex flex-row w-full bg-[var(--greyBlue)]">
        <section
            class="w-7/10 m-1 border-2 border-[var(--darkBlue)] bg-white rounded-lg shadow-md p-4"
        >
            <GridLayout
                v-model:layout="layout"
                :col-num="totalColumns"
                :max-rows="totalRows"
                :row-height="100"
                :is-draggable="editMode"
                :is-resizable="editMode"
                :vertical-compact="false"
                use-css-transforms
                prevent-collision
                :class="{ 'edit-mode': editMode }"
                class="min-h-[880px]"
            >
                <GridItem
                    v-for="item in layout"
                    :key="item.i"
                    :x="item.x"
                    :y="item.y"
                    :w="item.w"
                    :h="item.h"
                    :i="item.i"
                    :min-h="getMinDimensions(item.i).height"
                    :min-w="getMinDimensions(item.i).width"
                    @resize="handleResize"
                    @resized="handleResize"
                    class="h-full"
                >
                    <div class="h-full min-h-0 flex flex-col overflow-hidden">
                        <component
                            :is="getComponent(item.i)"
                            :editMode="editMode"
                            :dashboardData="item.itemData"
                            @updateDashboardData="item.itemData = $event"
                            @fileProcessing="processingUpload = $event"
                        />
                    </div>
                    <button
                        v-if="editMode"
                        @click="deleteComponent(item.i)"
                        class="absolute top-1 right-1 bg-red-500 text-white px-2 py-1 text-sm rounded shadow hover:bg-red-600"
                    >
                        ✕
                    </button>
                </GridItem>
                <!-- Empty Slots -->
                <GridItem
                    v-if="editMode"
                    v-for="slot in emptySlots"
                    :data-slot="slot.i"
                    :key="slot.i"
                    :x="slot.x"
                    :y="slot.y"
                    :w="slot.w"
                    :h="slot.h"
                    :i="slot.i"
                    :is-draggable="false"
                    :is-resizable="false"
                    class="bg-gray-100 border-dashed border-2 border-gray-400 flex items-center justify-center"
                >
                    <!-- Native Select Dropdown for Adding Components -->
                    <select
                        v-if="editMode"
                        @change="handleSelectChange($event, slot.x, slot.y)"
                        class="bg-white border border-gray-300 rounded px-2 py-1 shadow cursor-pointer"
                    >
                        <option value="" selected disabled>+</option>
                        <option
                            v-for="component in availableComponents"
                            :key="component.type"
                            :value="component.type"
                        >
                            {{ component.name }}
                        </option>
                    </select>
                </GridItem>
            </GridLayout>
        </section>
        <aside
            class="w-3/10 m-1 border-2 border-[var(--darkBlue)] bg-[var(--greyBlueAlt)] rounded-lg shadow-lg p-4 flex flex-col items-center"
        >
            <DashboardInfo
                :editMode="editMode"
                :info="info"
                :canSave="!processingUpload"
                @enableEdit="enableEditMode"
                @saveEdit="saveLayout"
                @cancelEdit="cancelLayout"
                @publishLayout="publishLayout"
            />
        </aside>
        <!-- Modal popup-->
        <transition name="modal" class="z-9000">
            <!-- Modal Background -->
            <div
                v-if="showModal"
                class="fixed inset-0 bg-gray-800/50 flex items-center justify-center"
                @click.self="closeModal"
            >
                <!-- Modal Body -->
                <div
                    class="bg-white rounded shadow-lg h-[90vh] w-[90vw] max-w-3xl p-6 m-2 relative flex flex-col"
                >
                    <!-- X Button -->
                    <button
                        class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
                        @click="closeModal"
                    >
                        &#10006;
                    </button>

                    <!-- Modal Content Scrollable Area -->
                    <div class="flex flex-col overflow-auto h-full w-full">
                        <h1 class="text-2xl pt-2 pb-4 text-center">
                            Publish Dashboard
                        </h1>

                        <!-- AddDeployment Form -->
                        <AddDeployment :layout="layout" :info="info" />
                    </div>
                </div>
            </div>
        </transition>
    </body>
</template>
<style scoped>
.edit-mode::before {
    position: absolute;
    width: calc(100% - 5px);
    height: calc(100% - 5px);
    margin: 5px;
    content: "";
    background-image:
        linear-gradient(to right, lightgrey 1px, transparent 1px),
        linear-gradient(to bottom, lightgrey 1px, transparent 1px);
    background-repeat: repeat;
    background-size: calc(calc(100% - 2px) / 4) 110px;
}
</style>
