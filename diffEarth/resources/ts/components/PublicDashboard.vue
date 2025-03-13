<script setup lang="ts">
import Header from "./Header.vue";
import PlotlyGraph from "./PlotlyGraph.vue";
import { useTranslation } from "../composables/useTranslation";
const { t } = useTranslation();
import DashboardInfo from "./DashboardInfo.vue";
import DashboardTable from "./DashboardTable.vue";
import { GridLayout, GridItem } from "grid-layout-plus";
import AddDeployment from "./AddDeployment.vue";
import { onMounted, ref, nextTick } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const uuid = route.params.uuid;

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
const info = ref("");
const name = ref("");
const loading = ref(true);

const totalColumns = 4;
const totalRows = 8;

const availableComponents = ref([
    { name: "graph", type: "graph" },
    { name: "table", type: "table" },
]);

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

const fetchDeployment = async () => {
    try {
        const res = await fetch(`/api/dashboard/${uuid}`);
        const data = await res.json();
        layout.value = data.layout;
        info.value = data.info;
        name.value = data.name;
    } catch (err) {
        console.error("Failed to load deployment:", err);
    } finally {
        loading.value = false;
        nextTick(() => window.dispatchEvent(new Event("resize")));
    }
};

onMounted(async () => {
    handleResize();
    await fetchDeployment();
});
</script>

<template>
    <Header />
    <div
        v-if="!layout || layout.length === 0"
        class="text-center text-gray-500 py-10"
    >
        <p>This deployment doesn't have a layout.</p>
    </div>
    <body v-else class="flex flex-row w-full bg-[var(--greyBlue)]">
        <section
            class="w-7/10 m-1 border-2 border-[var(--darkBlue)] bg-white rounded-lg shadow-md p-4"
        >
            <GridLayout
                v-model:layout="layout"
                :col-num="totalColumns"
                :max-rows="totalRows"
                :row-height="100"
                :is-draggable="false"
                :is-resizable="false"
                :vertical-compact="false"
                use-css-transforms
                prevent-collision
                :class="{ 'edit-mode': false }"
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
                        />
                    </div>
                </GridItem>
            </GridLayout>
        </section>
        <aside
            class="w-3/10 m-1 border-2 border-[var(--darkBlue)] bg-[var(--greyBlueAlt)] rounded-lg shadow-lg p-4 flex flex-col items-center"
        >
            <DashboardInfo
                :editMode="editMode"
                :info="info"
                readOnly
                :canSave="false"
            />
        </aside>
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
