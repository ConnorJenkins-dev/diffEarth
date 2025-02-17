<script setup>
import DashboardButton from "./DashboardButton.vue";
import FileUpload from "./FileUpload.vue";
import PlotlyGraph from "./PlotlyGraph.vue";
import Header from "./Header.vue";
</script>

<template>
    <Header />
    <body class="flex flex-row w-full">
        <section class="m-3 flex shrink w-7/10 flex-col">
            <div id="nav" class="">
                <nav class="flex w-full">
                    <ul class="flex flex-wrap justify-between w-full">
                        <li class="flex-1 text-center">
                            <dashboard-button />
                        </li>
                        <li class="flex-1 text-center">
                            <dashboard-button />
                        </li>
                        <li class="flex-1 text-center">
                            <dashboard-button />
                        </li>
                        <li class="flex-1 text-center">
                            <dashboard-button />
                        </li>
                        <li class="flex-1 text-center">
                            <dashboard-button />
                        </li>
                        <li class="flex-1 text-center">
                            <dashboard-button />
                        </li>
                        <li class="flex-1 text-center">
                            <dashboard-button />
                        </li>
                        <li class="flex-1 text-center">
                            <dashboard-button />
                        </li>
                    </ul>
                </nav>
            </div>
            <section id="maps" class="flex items-center justify-center">
                <PlotlyGraph />
            </section>
        </section>
        <aside class="m-3">
            <map class="m-3 margin-auto">
                <img
                    src="https://cdn-icons-png.flaticon.com/512/1865/1865269.png"
                    class="flex"
                />
            </map>
            <section id="list" class="">
                <div class="flex justify-center">
                    <FileUpload />
                </div>
                <ul>
                    <li class="p-3">
                        <h4 class="text-black text-center underline">
                            List Item
                        </h4>
                        <p class="text-black">
                            Supporting line text lorem ipsum dolor sit amet,
                            consectetur
                        </p>
                    </li>
                    <li class="p-3">
                        <h4 class="text-black text-center underline">
                            List Item
                        </h4>
                        <p class="text-black">
                            Supporting line text lorem ipsum dolor sit amet,
                            consectetur
                        </p>
                    </li>
                    <li class="p-3">
                        <h4 class="text-black text-center underline">
                            List Item
                        </h4>
                        <p class="text-black">
                            Supporting line text lorem ipsum dolor sit amet,
                            consectetur
                        </p>
                    </li>
                    <li class="p-3">
                        <h4 class="text-black text-center underline">
                            List Item
                        </h4>
                        <p class="text-black">
                            Supporting line text lorem ipsum dolor sit amet,
                            consectetur
                        </p>
                    </li>
                    <li class="p-3">
                        <h4 class="text-black text-center underline">
                            List Item
                        </h4>
                        <p class="text-black">
                            Supporting line text lorem ipsum dolor sit amet,
                            consectetur
                        </p>
                    </li>
                </ul>
            </section>
        </aside>
    </body>
</template>

<style scoped></style>
