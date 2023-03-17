import { createRouter, createWebHistory } from "vue-router";
import HomeView from "@/views/HomeView.vue";
import LoginView from "@/views/LoginView.vue";
import RegisterView from "@/views/RegisterView.vue";
import ProfileView from "@/views/ProfileView.vue";
import UpdateUserView from "@/views/UpdateUserView.vue";
import AnnonceView from "@/views/AnnonceView.vue";
import NewAnnoncesView from "@/views/NewAnnoncesView.vue";
import { useCookies } from "@vueuse/integrations/useCookies";
import ResetPasswordView from "@/views/ResetPasswordView.vue";
import { ENTRYPOINT } from "../../config/entrypoint";
import ForgotPasswordView from "@/views/ForgotPasswordView.vue";

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
      path: "/annonces",
      name: "annonces",
      component: AnnonceView,
    },
    {
      path: "/annoncescreate",
      name: "annonces_create",
      component: NewAnnoncesView,
    },
    {
      path: "/forgot-password",
      name: "forgot-password",
      component: ForgotPasswordView,
    },
    {
      path: "/reset-password/:token",
      name: "reset-password",
      component: ResetPasswordView,
      beforeEnter: async (to, from, next) => {
        const token = to.params.token;
        const response = await fetch(`${ENTRYPOINT}/forgot-password/${token}`, {
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
          },
        });
        if (response.ok) {
          next();
        } else {
          next("/login");
        }
      },
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
      path: "/update-user",
      name: "Update_user",
      component: UpdateUserView,
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
