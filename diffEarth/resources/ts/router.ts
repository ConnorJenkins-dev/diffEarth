import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";
import UserDashboard from "./components/UserDashboard.vue";
import AboutUs from "./components/AboutUs.vue";
import PageNotFound from "./components/PageNotFound.vue";
import MapPage from "./components/MapPage.vue";

const routes: Array<RouteRecordRaw> = [
    { path: "/", redirect: "/home" },
    { path: "/home", component: MapPage },
    { path: "/dashboard", component: UserDashboard },
    { path: "/about/:id", component: AboutUs },
    { path: "/about", redirect: "/about/1" },
    { path: "/map", component: MapPage },
    { path: "/:catchAll(.*)", component: PageNotFound },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("token");
    const userRole = localStorage.getItem("role");

    // If the route requires authentication but no token is found, redirect to home
    if (to.meta.requiresAuth && !token) {
        next("/");
        return;
    }

    // If the route has a specific role requirement and the user doesn't match, redirect to home
    if (to.meta.requiresRole && userRole !== to.meta.requiresRole) {
        alert("Access denied: You do not have permission to view this page.");
        next("/");
        return;
    }

    next(); // Allow access if all checks pass
});

export default router;
