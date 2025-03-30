import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";
import UserDashboard from "./components/UserDashboard.vue";
import AboutUs from "./components/AboutUs.vue";
import PageNotFound from "./components/PageNotFound.vue";
import MapPage from "./components/MapPage.vue";
import ContactUs from "./components/ContactUs.vue";
import Resources from "./components/Resources.vue";
import PublicDashboard from "./components/PublicDashboard.vue";
import ControlPanel from "./components/ControlPanel.vue";

const routes: Array<RouteRecordRaw> = [
    { path: "/", redirect: "/home" },
    { path: "/home", component: MapPage },
    {
        path: "/dashboard",
        component: UserDashboard,
        meta: { requiresRole: ["admin", "collaborator"] },
    },
    { path: "/dashboard/:uuid", component: PublicDashboard },
    { path: "/about/:id", component: AboutUs },
    { path: "/about", redirect: "/about/1" },
    { path: "/map", component: MapPage },
    { path: "/contactus", component: ContactUs },
    { path: "/resources", component: Resources },
    {
        path: "/admin",
        component: ControlPanel,
        meta: { requiresRole: "admin" },
    },
    { path: "/:catchAll(.*)", component: PageNotFound },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("token");
    const roles = localStorage.getItem("role");

    // If no roles are stored, deny access to admin pages
    if (!roles) {
        console.log("No roles found in localStorage");
        if (to.meta.requiresRole) {
            alert(
                "Access denied: You do not have permission to view this page.",
            );
            next("/"); // Redirect to home if no roles
            return;
        }
    }

    // Attempt to parse the roles or handle it if it's a single string
    let userRoles = [];

    if (roles) {
        try {
            // If it's a valid JSON array, parse it
            console.log("got a role: ", roles);
            userRoles = JSON.parse(roles);
        } catch (e) {
            // Check if roles are stored as a comma-separated string (e.g., "collaborator,organization")
            if (roles.includes(",")) {
                userRoles = roles.split(",");
            } else {
                userRoles = [roles]; // Wrap into an array
            }
            console.log(userRoles);
        }
    }

    // If the route requires authentication but no token is found, redirect to home
    if (to.meta.requiresAuth && !token) {
        console.log("No token found");
        next("/");
        return;
    }

    // If the route requires a role and the user doesn't have the required role
    if (to.meta.requiresRole) {
        const requiredRoles = to.meta.requiresRole;

        // Check if at least one role from the user's roles is in the requiredRoles array
        const hasAccess = userRoles.some((role) =>
            requiredRoles.includes(role),
        );

        if (!hasAccess) {
            console.log(
                `User roles: ${userRoles}, Required roles: ${requiredRoles}`,
            );
            alert(
                "Access denied: You do not have permission to view this page.",
            );
            next("/");
            return;
        }
    }
    next();
});

export default router;
