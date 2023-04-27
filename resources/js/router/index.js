import { createRouter, createWebHistory } from "vue-router";
import store from "@/store";
const routes = [
    {
        name: "login-route",
        path: "/login",
        component: () => import("@/components/auth/Login.vue"),
        meta: {
            middleware: "guest",
        },
    },
    {
        name: "register-route",
        path: "/register",
        component: () => import("@/components/auth/Register.vue"),
        meta: {
            middleware: "guest",
        },
    },
    {
        name: "dashboard-route",
        path: "/",
        component: () => import("@/components/views/Dashboard.vue"),
        meta: {
            middleware: "auth",
        },
    },
    {
        name: "info-route",
        path: "/info",
        component: () => import("@/components/views/Info.vue"),
        meta: {
            middleware: "auth",
        },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.meta.middleware == "guest") {
        if (store.state.auth.authenticated) {
            next({ name: "dashboard-route" });
        } else {
            next();
        }
    } else {
        if (store.state.auth.authenticated) {
            next();
        } else {
            next({ name: "login-route" });
        }
    }
});

export default router;
