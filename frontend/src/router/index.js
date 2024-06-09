import { createRouter, createWebHistory } from "vue-router";
import store from '../store'; 
import Login from "@/components/UserLogin.vue";
import Register from "@/components/UserRegister.vue";
import FileManagement from "@/components/FileManagement.vue";
import RecentItems from "@/components/RecentItems.vue";
import DeletedItems from "@/components/DeletedItems.vue";

const routes = [
  { path: "/login", component: Login },
  { path: "/register", component: Register },
  { path: "/", component: FileManagement, meta: { requiresAuth: true } },
  {
    path: "/recent",
    name: "RecentItems",
    component: RecentItems,
  },
  {
    path: "/trash",
    name: "DeletedItems",
    component: DeletedItems,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const loggedIn = store.state.user;
  if (to.matched.some((record) => record.meta.requiresAuth) && !loggedIn) {
    next("/login");
  } else {
    next();
  }
});

export default router;
