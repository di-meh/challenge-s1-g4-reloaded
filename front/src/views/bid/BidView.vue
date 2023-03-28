<template>
    <div
        class="flex w-full justify-center items-center h-full"
        v-for="bid in bids"
        :key="bid.id"
    >
        <div class="flex flex-col py-10 px-16 w-[500px]">
            <h2 class="text-3xl font-bold text-center">
                {{ bid.title }}
            </h2>
            <a :href="`/update-bid/${bid.id}`" class="text-sm mb-6 text-center"
                >Modifier</a
            >
            <h3 class="text-sm font-bold mb-6 text-center">
                Temps restant : ({{
                    new Date(bid.startDate).toUTCString().substring(25, 5)
                }})
            </h3>
            <h3 class="text-sm font-bold mb-6 text-center">
                Prix de départ : {{ bid.startPrice }} €
            </h3>
            <h3 class="text-sm font-bold mb-6 text-center">
                Prix actuel : {{ bid.actualPrice }} €
            </h3>
            <FormKit
                type="form"
                submit-label="Enchérir"
                :classes="{
                    form: 'space-y-6',
                }"
                :submit-attrs="{
                    outerClass: 'pt-4',
                    inputClass:
                        'w-full rounded-md bg-indigo-600 py-2.5 px-3.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600',
                }"
                @submit="submit"
            >
                <FormKit
                    type="number"
                    :min="`${bid.actualPrice + 5}`"
                    step="0.01"
                    name="userPrice"
                    validation="required"
                    label="Saisir votre enchère"
                    :classes="{
                        input: 'mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
                    }"
                />
            </FormKit>
        </div>
    </div>
</template>

<script setup>
import { participateBid, getBidById } from "@/services/bid";
import { FormKit } from "@formkit/vue";
import { ref, onBeforeMount } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useToast } from "vue-toastification";

const route = useRoute();
const bids = ref([]);

onBeforeMount(() => {
    getBidById(route.params.id, bids);
});
const router = useRouter();

const submit = async (values) => {
    const toast = useToast();
    if (values.userPrice < bids.value[0].actualPrice + 5) {
        toast.error("Votre enchère doit être supérieure de 5€");
        return;
    }
    const response = await participateBid(bids.value[0].id, values.userPrice);

    if (response.ok) {
        toast.success("Vous avez enchéri avec succès !");
        await router.push("/bids");
    } else {
        toast.error(
            "Une erreur est survenue veuillez réessayer ultérieurement"
        );
    }
};
</script>
