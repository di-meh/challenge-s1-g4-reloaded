<script setup>
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
    type: String,
    entrepriseLink: String,
    entrepriseName: String,
    deleteDemande: Function,
});

const acceptVendeur = async () => {
    const userId = props.userId.split("/").pop();
    const demandeId = props.demandeId.split("/").pop();
    const response = await fetch(
        `${ENTRYPOINT}/users/${userId}/update_vendeur`,
        {
            method: "PATCH",
            headers: {
                "Content-Type": "application/merge-patch+json",
                Authorization: `Bearer ${token}`,
            },
            body: JSON.stringify({
                roles: ["ROLE_VENDEUR"],
                tel: props.tel,
                adresse: props.adresse,
            }),
        }
    );
    if (response.ok) {
        const deleteDemande = await fetch(
            `${ENTRYPOINT}/demandes/${demandeId}`,
            {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`,
                },
            }
        );
        if (deleteDemande.ok) {
            toast.success("Demande acceptée");
            props.deleteDemande(demandeId);
        } else {
            toast.error("Erreur");
        }
    } else {
        toast.error("Erreur");
    }
};

const acceptAnnonceur = async () => {
    const userId = props.userId.split("/").pop();
    const demandeId = props.demandeId.split("/").pop();
    const response = await fetch(
        `${ENTRYPOINT}/users/${userId}/update_annonceur`,
        {
            method: "PATCH",
            headers: {
                "Content-Type": "application/merge-patch+json",
                Authorization: `Bearer ${token}`,
            },
            body: JSON.stringify({
                roles: ["ROLE_ANNONCEUR"],
                tel: props.tel,
                adresse: props.adresse,
                entrepriseLink: props.entrepriseLink,
                entrepriseName: props.entrepriseName,
            }),
        }
    );
    if (response.ok) {
        const deleteDemande = await fetch(
            `${ENTRYPOINT}/demandes/${demandeId}`,
            {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`,
                },
            }
        );
        if (deleteDemande.ok) {
            toast.success("Demande acceptée");
            props.deleteDemande(demandeId);
        } else {
            toast.error("Erreur");
        }
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
        props.deleteDemande(demandeId);
    } else {
        toast.error("Erreur");
    }
};
</script>
<template>
    <li
        class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow flex flex-col justify-between"
    >
        <h3 class="truncate text-l font-medium text-black text-center p-2">
            Type : {{ type }}
        </h3>
        <div class="flex w-full items-center justify-between space-x-6 p-6">
            <div class="flex-1 truncate">
                <div class="flex items-center space-x-3">
                    <h3 class="truncate text-sm font-medium text-gray-900">
                        Nom : {{ username }}
                    </h3>
                </div>
                <p
                    v-if="type == 'Annonceur'"
                    class="mt-1 truncate text-sm text-gray-500"
                >
                    Entreprise : {{ entrepriseName }}
                </p>
                <p
                    v-if="type == 'Annonceur'"
                    class="mt-1 truncate text-sm text-gray-500"
                >
                    Lien : {{ entrepriseLink }}
                </p>
                <p class="mt-1 truncate text-sm text-gray-500">
                    Adresse : {{ adresse }}
                </p>
                <p class="mt-1 truncate text-sm text-gray-500">
                    Tel : {{ tel }}
                </p>
                <p class="mt-1 truncate text-sm text-gray-500">
                    Message : {{ message }}
                </p>
            </div>
        </div>
        <div>
            <div class="-mt-px flex divide-x divide-gray-200">
                <div class="flex w-0 flex-1">
                    <button
                        v-if="type == 'Annonceur'"
                        @click="acceptAnnonceur"
                        class="cursor-pointer text-green-600 relative -mr-px inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-bl-lg border border-transparent py-4 text-sm font-semibold text-gray-900"
                    >
                        Accept
                    </button>
                    <button
                        v-if="type == 'Vendeur'"
                        @click="acceptVendeur"
                        class="cursor-pointer text-green-600 relative -mr-px inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-bl-lg border border-transparent py-4 text-sm font-semibold text-gray-900"
                    >
                        Accept
                    </button>
                </div>
                <div class="-ml-px flex w-0 flex-1">
                    <button
                        @click="decline"
                        class="cursor-pointer text-red-600 relative inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-br-lg border border-transparent py-4 text-sm font-semibold text-gray-900"
                    >
                        Decline
                    </button>
                </div>
            </div>
        </div>
    </li>
</template>
