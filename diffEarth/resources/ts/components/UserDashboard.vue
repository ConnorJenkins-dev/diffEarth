<script setup>
import DashboardButton from "./DashboardButton.vue";
import FileUpload from "./FileUpload.vue";
import Header from "./Header.vue";
import PlotlyGraph from "./PlotlyGraph.vue";
import EmailForm from "./EmailForm.vue";
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
                            <DashboardButton> Button 1</DashboardButton>
                        </li>
                        <li class="flex-1 text-center">
                            <DashboardButton> Button 2</DashboardButton>
                        </li>
                        <li class="flex-1 text-center">
                            <DashboardButton> Button 3</DashboardButton>
                        </li>
                        <li class="flex-1 text-center">
                            <DashboardButton> Button 4</DashboardButton>
                        </li>
                        <li class="flex-1 text-center">
                            <DashboardButton> Button 5</DashboardButton>
                        </li>
                        <li class="flex-1 text-center">
                            <DashboardButton> Button 6</DashboardButton>
                        </li>
                        <li class="flex-1 text-center">
                            <DashboardButton> Button 7</DashboardButton>
                        </li>
                        <li class="flex-1 text-center">
                            <DashboardButton> Button 8</DashboardButton>
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
                <ul>
                    <li
                        class="p-3 border-b border-[var(--darkBlue)] last:border-0"
                    >
                        <h4
                            class="text-[var(--darkBlueAlt)] text-center underline font-medium"
                        >
                            List Item
                        </h4>
                        <p class="text-[var(--richBlue)] text-sm">
                            Supporting line text lorem ipsum dolor sit amet,
                            consectetur
                        </p>
                    </li>
                </ul>
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
