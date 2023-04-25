import { createRouter, createWebHistory } from "vue-router";
import HomeView from "@/views/HomeView.vue";
import LoginView from "@/views/LoginView.vue";
import RegisterView from "@/views/RegisterView.vue";
import ProfileView from "@/views/ProfileView.vue";
import UpdateUserView from "@/views/UpdateUserView.vue";
import DemandeVendeurView from "@/views/DemandeVendeurView.vue";
import DemandeAnnonceurView from "@/views/DemandeAnnonceurView.vue";
import AdminDemandesView from "@/views/AdminDemandesView.vue";

import { useCookies } from "@vueuse/integrations/useCookies";
import ResetPasswordView from "@/views/ResetPasswordView.vue";
import { ENTRYPOINT } from "../../config/entrypoint";
import ForgotPasswordView from "@/views/ForgotPasswordView.vue";
import jwtDecode from "jwt-decode";
import { useToast } from "vue-toastification";

const cookies = useCookies();
const toast = useToast();

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
      path: "/vendeur",
      name: "demande_vendeur",
      component: DemandeVendeurView,
      beforeEnter: (to, from, next) => {
        const token = cookies.get("token");
        if (localStorage.getItem("user") && cookies.get("token")) {
          const decodedToken = jwtDecode(token);
          if (decodedToken.roles.includes("ROLE_VENDEUR")) {
            toast.error("Vous êtes déjà vendeur");
          } else {
            next();
          }
        } else {
          next("/login");
        }
      },
    },
    {
      path: "/annonceur",
      name: "demande_annonceur",
      component: DemandeAnnonceurView,
      beforeEnter: (to, from, next) => {
        const token = cookies.get("token");
        if (localStorage.getItem("user") && cookies.get("token")) {
          const decodedToken = jwtDecode(token);
          if (decodedToken.roles.includes("ROLE_ANNONCEUR")) {
            toast.error("Vous êtes déjà annonceur");
          } else {
            next();
          }
        } else {
          next("/login");
        }
      },
    },
    {
      path: "/admin/demandes",
      name: "demandes",
      component: AdminDemandesView,
      beforeEnter: (to, from, next) => {
        const token = cookies.get("token");
        if (localStorage.getItem("user") && cookies.get("token")) {
          const decodedToken = jwtDecode(token);
          if (decodedToken.roles.includes("ROLE_ADMIN")) {          
            next();
          } else {
            toast.error("Vous devez être administrateur pour accéder à cette page");
            next('/login');
          }
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
