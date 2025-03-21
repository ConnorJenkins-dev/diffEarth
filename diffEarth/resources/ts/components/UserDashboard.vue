<script setup lang="ts">
import DashboardButton from "./DashboardButton.vue";
import FileUpload from "./FileUpload.vue";
import Header from "./Header.vue";
import DatasetView from "./DatasetView.vue";
import PlotlyGraph from "./PlotlyGraph.vue";
import EmailForm from "./EmailForm.vue";
import { useTranslation } from "../composables/useTranslation";
const { t } = useTranslation();
</script>

<template>
    <Header />
    <body class="flex flex-row w-full bg-[var(--greyBlue)]">
        <section
            class="w-7/10 m-1 border-2 border-[var(--darkBlue)] bg-white rounded-lg shadow-md p-4"
        >
            <div id="nav" class="p-4">
                <nav class="flex w-full">
                    <ul class="flex flex-wrap justify-between w-full">
                        <li class="flex-1 text-center">
                            <DashboardButton>{{ t.button1 }}</DashboardButton>
                        </li>
                        <li class="flex-1 text-center">
                            <DashboardButton>{{ t.button2 }}</DashboardButton>
                        </li>
                        <li class="flex-1 text-center">
                            <DashboardButton>{{ t.button3 }}</DashboardButton>
                        </li>
                        <li class="flex-1 text-center">
                            <DashboardButton>{{ t.button4 }}</DashboardButton>
                        </li>
                        <li class="flex-1 text-center">
                            <DashboardButton>{{ t.button5 }}</DashboardButton>
                        </li>
                        <li class="flex-1 text-center">
                            <DashboardButton>{{ t.button6 }}</DashboardButton>
                        </li>
                        <li class="flex-1 text-center">
                            <DashboardButton>{{ t.button7 }}</DashboardButton>
                        </li>
                        <li class="flex-1 text-center">
                            <DashboardButton>{{ t.button8 }}</DashboardButton>
                        </li>
                    </ul>
                </nav>
            </div>
            <section id="maps" class="flex items-center justify-center">
                <PlotlyGraph />
            </section>
        </section>

        <aside
            class="w-3/10 m-1 border-2 border-[var(--darkBlue)] bg-[var(--greyBlueAlt)] rounded-lg shadow-lg p-4 flex flex-col items-center"
        >
            <map class="mb-2">
                <img
                    src="https://cdn-icons-png.flaticon.com/512/1865/1865269.png"
                    class="flex"
                />
            </map>
            <section
                id="list"
                class="border-4 border-[var(--darkBlue)] w-full rounded-md p-4 bg-white"
            >
                <div class="flex justify-center">
                    <FileUpload />
                </div>
                <DatasetView />
            </section>
            <section
                id="email"
                class="border-4 border-[var(--darkBlue)] w-full rounded-md p-4 bg-white"
            >
                <div class="flex justify-center">
                    <EmailForm />
                </div>
            </section>
        </aside>
    </body>
</template>
<style scoped></style>
