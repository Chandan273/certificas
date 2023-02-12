import { createWebHistory, createRouter } from "vue-router";
import Auth from "./Auth.js";

const routes = [
    {
        name: "home",
        path: "/",
        redirect: {
            name: "login",
            path: "/Login",
            component: () => import("./pages/Login.vue"),
        },
    },
    {
        name: "login",
        path: "/login",
        component: () => import("./pages/Login.vue"),
    },
    {
        name: "forgot-password",
        path: "/forgot-password",
        component: () => import("./pages/ForgotPassword.vue"),
    },
    {
        name: "reset-password",
        path: "/reset-password",
        component: () => import("./pages/ResetPassword.vue"),
    },
    {
        name: "pagenotfound",
        path: "/:pathMatch(.*)",
        component: () => import("./pages/PageNotFound.vue"),
    },

    // Auth Routes
    {
        name: "dashboard",
        path: "/dashboard",
        meta: { requiresAuth: true },
        component: () => import("./pages/admin/index.vue"),
    },
    {
        name: "profile",
        path: "/profile",
        meta: { requiresAuth: true },
        component: () => import("./pages/admin/profile.vue"),
    },
    {
        name: "customers",
        path: "/customers",
        meta: { requiresAuth: true },
        component: () => import("./pages/admin/customers.vue"),
    },
    {
        name: "students",
        path: "/students",
        meta: { requiresAuth: true },
        component: () => import("./pages/admin/students.vue"),
    },
    {
        name: "courses",
        path: "/courses",
        meta: { requiresAuth: true },
        component: () => import("./pages/admin/courses.vue"),
    },
    {
        name: "tenant",
        path: "/tenants",
        meta: { requiresAuth: true },
        component: () => import("./pages/admin/tenants.vue"),
    },
    {
        name: "certificates",
        path: "/certificates",
        meta: { requiresAuth: true },
        component: () => import("./pages/admin/certificates.vue"),
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.matched.some((record) => record.meta.requiresAuth)) {
        if (Auth.check()) {
            next();
            return;
        } else {
            router.push("/login");
        }
    } else {
        next();
    }
});

export default router;
