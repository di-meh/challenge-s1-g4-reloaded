<script setup>
import { ref, onBeforeMount } from "vue";
import { ENTRYPOINT } from "../../config/entrypoint";
import { useCookies } from "@vueuse/integrations/useCookies";
import { useToast } from "vue-toastification";
const cookies = useCookies();
const toast = useToast();
let token = cookies.get("token");


const props = defineProps({
    username: String,
    adresse: String,
    message: String,
    tel: Number,
    state: String,
    userId: String,
    demandeId: String,
});

const accept = async () => {
    const userId = props.userId.split("/").pop();
    const demandeId = props.demandeId.split("/").pop();
    const response = await fetch(`${ENTRYPOINT}/users/${userId}`, {
        method: "PATCH",
        headers: {
            "Content-Type": "application/merge-patch+json",
            Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({
            roles: ["ROLE_ANNONCEUR"],
            tel: props.tel,
            adresse: props.adresse,
            isAnnonceur: true,
        }),
    });
    const deleteDemande = await fetch(`${ENTRYPOINT}/demandes/${demandeId}`, {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${token}`,
        },
    });
    if (response.ok && deleteDemande.ok) {
        toast.success("Demande acceptée");
    } else {
        toast.error("Erreur");
    }
};

const decline = async () => {
    const demandeId = props.demandeId.split("/").pop();
    const deleteDemande = await fetch(`${ENTRYPOINT}/demandes/${demandeId}`, {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${token}`,
        },
    });
    if (deleteDemande.ok) {
        toast.success("Demande refusée");
    } else {
        toast.error("Erreur");
    }
};

</script>
<template>
    <div>
        <p>{{ username }}</p>
        <p>{{ adresse }}</p>
        <p>{{ message }}</p>
        <p>{{ tel }}</p>
        <p>{{ state }}</p>
        <div>
            <button @click="accept">Accepter</button>
            <button @click="decline">Refuser</button>
        </div>
    </div>
</template>