<template>
    <div class="flex w-full justify-center items-center h-full">
        <div class="flex flex-col py-10 px-16 w-[500px]">
            <h2 class="text-3xl font-bold mb-6 text-center">
                Demande Annonceur
            </h2>
            <FormKit
                type="form"
                submit-label="Envoyer la demande"
                :classes="{
                    form: 'space-y-6',
                }"
                :submit-attrs="{
                    outerClass: 'pt-4',
                    inputClass:
                        'w-full rounded-md bg-indigo-600 py-2.5 px-3.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600',
                }"
                @submit="demandeAnnonceur"
            >
                <FormKit
                    type="text"
                    name="entreprise_name"
                    validation="required"
                    label="Nom de l'entreprise"
                    placeholder="Nom de l'entreprise"
                    :classes="{
                        input: 'mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
                    }"
                />
                <FormKit
                    type="text"
                    name="entreprise_link"
                    validation="required"
                    label="Lien de l'entreprise"
                    placeholder="Lien de l'entreprise"
                    :classes="{
                        input: 'mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
                    }"
                />
                <FormKit
                    type="text"
                    name="adresse"
                    validation="required"
                    label="Adresse"
                    placeholder="Adresse"
                    :classes="{
                        input: 'mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
                    }"
                />

                <FormKit
                    type="number"
                    name="tel"
                    label="Téléphone"
                    placeholder="Téléphone"
                    validation="required|isPhoneNumber"
                    :validation-rules="{ isPhoneNumber }"
                    :classes="{
                        input: 'mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
                    }"
                />
                <FormKit
                    type="textarea"
                    name="message"
                    validation="required"
                    label="Message"
                    placeholder="Message"
                    :classes="{
                        input: 'mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
                    }"
                />
            </FormKit>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from "vue-router";
import { FormKit } from "@formkit/vue";
import { useToast } from "vue-toastification";
import { useCookies } from "@vueuse/integrations/useCookies";
import { ENTRYPOINT } from "../../config/entrypoint";

const cookies = useCookies();
const toast = useToast();
let token = cookies.get("token");
const router = useRouter();

function isPhoneNumber(node) {
    const frPhoneNumber = /^0[1-78]([-. ]?[0-9]{2}){4}$/;
    const value = node.value;
    return frPhoneNumber.test(value);
}

const demandeAnnonceur = async (data) => {
    let demandeAnnonceur = await fetch(ENTRYPOINT + "/demandes", {
        method: "POST",
        headers: {
            Authorization: "Bearer " + token,
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            adresse: data.adresse,
            tel: data.tel,
            message: data.message,
            state: "En attente",
            type: "Annonceur",
            entrepriseName: data.entreprise_name,
            entrepriseLink: data.entreprise_link,
        }),
    });

    if (demandeAnnonceur.status === 201) {
        toast.success("Demande envoyée avec succès");
        router.push("/");
    } else {
        toast.error("Vous avez déjà envoyé une demande");
        router.push("/");
    }
};
</script>
