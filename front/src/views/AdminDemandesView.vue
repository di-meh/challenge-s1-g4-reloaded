<template>
    <div class="text-white m-2">
        <h1 class="text-xl font-bold mb-2">Demandes</h1>
        <div v-if="demandes.length === 0">
            <p>Aucunes demandes</p>
        </div>
        <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <DemandesComponent
                v-for="demande in demandes.value"
                :key="demande['@id']"
                :username="demande.owner.username"
                :userId="demande.owner['@id']"
                :demandeId="demande['@id']"
                :adresse="demande.adresse"
                :message="demande.message"
                :tel="demande.tel"
                :state="demande.state"
                :type="demande.type"
                :entrepriseLink="demande.entrepriseLink"
                :entrepriseName="demande.entrepriseName"
                :deleteDemande="deleteDemande"
            />
        </ul>
    </div>
</template>

<script setup>

import { ref, reactive, onBeforeMount } from "vue";
import { ENTRYPOINT } from "../../config/entrypoint";
import { useRouter } from "vue-router";
import DemandesComponent from "../components/DemandesComponent.vue";
import { useCookies } from "@vueuse/integrations/useCookies";

const cookies = useCookies();
let token = cookies.get("token");

const router = useRouter();
const demandes = reactive([]);

function deleteDemande(id) {
    demandes.value = demandes.value.filter((demande) => demande['@id'].split("/").pop() !== id);
}

onBeforeMount(async () => {
    const response = await fetch(`${ENTRYPOINT}/demandes`, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token,
        },
    });
    const data = await response.json();
    demandes.value = data["hydra:member"];
});


</script>