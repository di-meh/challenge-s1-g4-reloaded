<template>
    <div class="text-white">
        <div
            class="mx-auto max-w-2xl py-16 px-4 sm:py-24 sm:px-6 lg:max-w-7xl lg:px-8"
        >
            <h2 class="text-xl font-bold mb-3">Annonces</h2>
            <p v-if="items.length <= 0" class="mb-3">Aucunes annonces</p>
            <RouterLink
                v-if="isConnected"
                to="/annonces/create"
                class="w-full rounded-md bg-indigo-600 py-2.5 px-3.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 text-center"
                ><button>Créer une annonce</button></RouterLink
            >
            <div
                class="mt-8 grid grid-cols-1 gap-y-12 sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-8"
            >
                <AnnonceCard
                    v-for="item in items"
                    :key="item.id"
                    :title="item.title"
                    :id="item.id"
                    :description="item.description"
                    :price="item.price"
                    :image="item.images[0].filePath"
                    style="cursor: pointer"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import AnnonceCard from "@/components/AnnonceCard.vue";
import { ref, onBeforeMount } from "vue";
import { ENTRYPOINT } from "../../config/entrypoint";
import { useCookies } from "@vueuse/integrations/useCookies";
import jwtDecode from "jwt-decode";

const cookies = useCookies();
const token = cookies.get("token");
let decoded = token ? jwtDecode(token) : null;
const isConnected = decoded ? decoded.roles.includes("ROLE_VENDEUR") : false;

let items = ref([]);

onBeforeMount(async () => {
    const response = await fetch(`${ENTRYPOINT}/annonces?exists[buyer]=false`);
    const data = await response.json();
    items.value = data["hydra:member"];
});
</script>
