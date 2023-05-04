import { createRouter, createWebHistory } from "vue-router";
import HomeView from "@/views/HomeView.vue";
import LoginView from "@/views/LoginView.vue";
import RegisterView from "@/views/RegisterView.vue";
import ProfileView from "@/views/ProfileView.vue";
import { useCookies } from "@vueuse/integrations/useCookies";
import ProductsView from "@/views/ProductsView.vue";
import AllProductsView from "@/views/AllProductsView.vue";
import Products from "@/views/Products.vue";
import Cart from "@/views/Cart.vue";
const cookies = useCookies();

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomeView,
    },
    {
      path: "/login",
      name: "login",
      component: LoginView,
    },
    {
      path: "/register",
      name: "register",
      component: RegisterView,
    },
    {
      path: "/products",
      name: "products",
      component: ProductsView,
    },
    {
      path: "/all-products",
      name: "all-products",
      component: AllProductsView,
    },
    {
      path: "/products-list",
      name: "products-list",
      component: Products,
    },
    {
      path: "/cart",
      name: "cart",
      component: Cart,
    },
    {
      path: "/profile",
      name: "profile",
      component: ProfileView,
      beforeEnter: (to, from, next) => {
        if (localStorage.getItem("user") && cookies.get("token")) {
          next();
        } else {
          next("/login");
        }
      },
    },
    {
      path: "/:pathMatch(.*)*",
      name: "not-found",
      component: () => import("../views/NotFoundView.vue"),
    },
  ],
});

export default router;
