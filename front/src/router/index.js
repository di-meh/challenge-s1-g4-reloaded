import { createRouter, createWebHistory } from "vue-router";
import HomeView from "@/views/HomeView.vue";
import LoginView from "@/views/LoginView.vue";
import RegisterView from "@/views/RegisterView.vue";
import ProfileView from "@/views/ProfileView.vue";
import UpdateUserView from "@/views/UpdateUserView.vue";
import CreateBidView from "@/views/bid/CreateBidView.vue";
import BidsView from "@/views/bid/BidsView.vue";
import BidView from "@/views/bid/BidView.vue";
import UpdateBidView from "@/views/bid/UpdateBidView.vue";
import DemandeVendeurView from "@/views/DemandeVendeurView.vue";
import DemandeAnnonceurView from "@/views/DemandeAnnonceurView.vue";
import AdminDemandesView from "@/views/AdminDemandesView.vue";
import AnnonceView from "@/views/AnnonceView.vue";
import NewAnnoncesView from "@/views/NewAnnoncesView.vue";
import ItemAnnonceView from "@/views/ItemAnnonceView.vue";
import { useCookies } from "@vueuse/integrations/useCookies";
import ResetPasswordView from "@/views/ResetPasswordView.vue";
import PaymentSuccess from "@/views/PaymentSuccessView.vue";
import PaymentCancel from "@/views/PaymentCancelView.vue";
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
      path: "/annonces",
      name: "annonces",
      component: AnnonceView,
    },
    {
      path: "/annonces/create",
      name: "annonces_create",
      component: NewAnnoncesView,
      beforeEnter: (to, from, next) => {
        const token = cookies.get("token");
        const decodedToken = jwtDecode(token);
        if (localStorage.getItem("user") && cookies.get("token")) {
          if (!decodedToken.roles.includes("ROLE_VENDEUR")) {
            toast.error("Vous devez être vendeur");
            next("/");
          } else {
            next();
          }
        } else {
          next("/login");
        }
      },
    },
    {
      path: "/annonces/:id",
      name: "annonces_id",
      component: ItemAnnonceView,
      beforeEnter: (to, from, next) => {
        if (localStorage.getItem("user") && cookies.get("token")) {
          next();
        } else {
          next("/login");
        }
      },
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
    },
    {
      path: "/bids/:id",
      name: "bid",
      component: BidView,
      beforeEnter: (to, from, next) => {
        if (localStorage.getItem("user") && cookies.get("token")) {
          next();
        } else {
          next("/login");
        }
      },
    },
    {
      path: "/bids",
      name: "bids",
      component: BidsView,
      beforeEnter: (to, from, next) => {
        if (localStorage.getItem("user") && cookies.get("token")) {
          next();
        } else {
          next("/login");
        }
      },
    },
    {
      path: "/create-bid",
      name: "create-bid",
      component: CreateBidView,
      beforeEnter: (to, from, next) => {
        if (localStorage.getItem("user") && cookies.get("token")) {
          next();
        } else {
          next("/login");
        }
      },
    },
    {
      path: "/update-bid/:id",
      name: "update-bid",
      component: UpdateBidView,
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
            toast.error(
              "Vous devez être administrateur pour accéder à cette page"
            );
            next("/login");
          }
        } else {
          next("/login");
        }
      },
    },
    {
      path: "/payment/success",
      name: "payment_success",
      component: PaymentSuccess,
    },
    {
      path: "/payment/cancel",
      name: "payment_cancel",
      component: PaymentCancel,
    },
    {
      path: "/:pathMatch(.*)*",
      name: "not-found",
      component: () => import("../views/NotFoundView.vue"),
    },
  ],
});

export default router;
