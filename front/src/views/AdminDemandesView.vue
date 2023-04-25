<template>
    <div class="text-white m-2">
        <h1 class="text-xl font-bold mb-2">Demandes</h1>
        <div v-if="demandes.length === 0">
            <p>Aucunes demandes</p>
        </div>
        <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <DemandesComponent
                v-for="demande in demandes"
                :key="demande.id"
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
            />
        </ul>
    </div>
</template>

<script setup>

import { ref, onBeforeMount } from "vue";
import { ENTRYPOINT } from "../../config/entrypoint";
import { useRouter } from "vue-router";
import DemandesComponent from "../components/DemandesComponent.vue";

const router = useRouter();
let demandes = ref([]);

onBeforeMount(async () => {
    const response = await fetch(`${ENTRYPOINT}/demandes`);
    const data = await response.json();
    demandes.value = data["hydra:member"];
});


</script>