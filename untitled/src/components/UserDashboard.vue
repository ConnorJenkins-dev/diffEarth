<script setup>
import { ref } from 'vue'
import FileUpload from '@/components/FileUpload.vue';

</script>
<style>

</style>

<template>
 <body>
 <FileUpload/>
  <section class="w-3/5 m-1 border-2 flex flex-row w-full">
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
  </section>
  <aside class="w-2/5 m-1 border-2">
    <map>
      <img src="https://cdn-icons-png.flaticon.com/512/1865/1865269.png" class="w-fit mx-3">
    </map>
  </aside>
 </body>


</template>

<style scoped>

</style>