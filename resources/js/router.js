import { createRouter, createWebHistory } from "vue-router";
import Login from "./Pages/Login.vue";
import Test from "./Pages/Test.vue";

const routes = [
    { path: "/login", component: Login },
    { path: "/", component: Test },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
