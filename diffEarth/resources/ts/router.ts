import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";
import UserDashboard from "./components/UserDashboard.vue";
import AboutUs from "./components/AboutUs.vue";
import PageNotFound from "./components/PageNotFound.vue";

const routes: Array<RouteRecordRaw> = [
    { path: "/", redirect: "/dashboard" },
    { path: "/home", redirect: "/dashboard" },
    { path: "/dashboard", component: UserDashboard },
    { path: "/about", component: AboutUs },
    { path: "/:catchAll(.*)", component: PageNotFound },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
