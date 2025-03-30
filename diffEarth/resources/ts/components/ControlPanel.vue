<script setup>
import Header from "./Header.vue";
import UpdateUserRole from "./UpdateUserRole.vue";
import CreateRole from "./CreateRole.vue";
</script>

<template>
    <Header />
    <div class="p-6 flex space-x-1">
        <UpdateUserRole />
        <CreateRole />
    </div>
</template>
