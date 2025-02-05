<script setup>
import { ref } from 'vue'
import PlaceholderGraph from "@/components/PlaceholderGraph.vue";


</script>
<style>

</style>

<template>
 <body class="flex flex-row w-full">
  <section class="w-7/10 m-1 border-2 items-center justify-center">
    <div id="nav" class="">
      <nav class="flex w-full">
        <ul class="flex flex-wrap justify-between w-full">
          <li class="flex-1 text-center">
            <button class="filter-button">Label</button>
          </li>
          <li class="flex-1 text-center">
            <button class="filter-button">Label</button>
          </li>
          <li class="flex-1 text-center">
            <button class="filter-button">Label</button>
          </li>
          <li class="flex-1 text-center">
            <button class="filter-button">Label</button>
          </li>
          <li class="flex-1 text-center">
            <button class="filter-button">Label</button>
          </li>
          <li class="flex-1 text-center">
            <button class="filter-button">Label</button>
          </li>
          <li class="flex-1 text-center">
            <button class="filter-button">Label</button>
          </li>
          <li class="flex-1 text-center">
            <button class="filter-button">Label</button>
          </li>
          <li class="flex-1 text-center">
            <button class="filter-button pi-filter">Filter</button>
          </li>
        </ul>
      </nav>
    </div>
    <section id="maps" class="grid items-center justify-center">
      <PlaceholderGraph />
      <PlaceholderGraph />
    </section>
  </section>
  <aside class="w-3/10 m-1 border-2">
    <map>
      <img src="https://cdn-icons-png.flaticon.com/512/1865/1865269.png" class="w-fit mx-3">
    </map>
    <section id="list" class="border-4 m-auto">
      <h3 class="text-center text-black">Title</h3>
      <ul>
        <li class="m-auto p-3">
          <h4 class="text-black text-center underline" >List Item</h4>
          <p class="text-black">Supporting line text lorem ipsum dolor sit amet, consectetur</p>
        </li>
        <li class="m-auto p-3">
          <h4 class="text-black text-center underline">List Item</h4>
          <p class="text-black">Supporting line text lorem ipsum dolor sit amet, consectetur</p>
        </li>
        <li class="m-auto p-3">
          <h4 class="text-black text-center underline">List Item</h4>
          <p class="text-black">Supporting line text lorem ipsum dolor sit amet, consectetur</p>
        </li>
        <li class="m-auto p-3">
          <h4 class="text-black text-center underline">List Item</h4>
          <p class="text-black">Supporting line text lorem ipsum dolor sit amet, consectetur</p>
        </li>
        <li class="m-auto p-3">
          <h4 class="text-black text-center underline">List Item</h4>
          <p class="text-black">Supporting line text lorem ipsum dolor sit amet, consectetur</p>
        </li>
      </ul>
    </section>
  </aside>
 </body>


</template>

<style scoped>

</style>