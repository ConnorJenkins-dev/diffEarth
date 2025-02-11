<script setup>
import PlaceholderGraph from "./PlaceholderGraph.vue";
import DashboardButton from "./DashboardButton.vue";
</script>

<template>
    <body class="flex flex-row w-full">
        <section class="w-7/10 m-1 items-center justify-center">
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
            <section id="maps" class="grid items-center justify-center">
                <PlaceholderGraph />
                <PlaceholderGraph />
            </section>
        </section>
        <aside class="w-3/10 m-1 items-center grid items-center justify-center">
            <map>
                <img
                    src="https://cdn-icons-png.flaticon.com/512/1865/1865269.png"
                    class="w-fit"
                />
            </map>
            <section id="list" class="">
                <h2 class="text-center text-black">Title</h2>
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
