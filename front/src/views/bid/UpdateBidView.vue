<template>
    <div
        class="flex w-full justify-center items-center h-full"
        v-for="bid in bids"
        :key="bid.id"
    >
        <div class="flex flex-col py-10 px-16 w-[500px]">
            <h2 class="text-3xl font-bold mb-6 text-center">
                Modifier {{ bid.title }}
            </h2>
            <FormKit
                type="form"
                submit-label="Modifier l'enchère"
                :classes="{
                    form: 'space-y-6',
                }"
                :submit-attrs="{
                    outerClass: '',
                    inputClass:
                        'w-full rounded-md bg-indigo-600 py-2.5 px-3.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600',
                }"
                @submit="submit"
            >
                <FormKit
                    type="text"
                    name="title"
                    :value="`${bid.title}`"
                    validation="required"
                    label="Titre"
                    :classes="{
                        input: 'mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
                    }"
                />
                <FormKit
                    type="datetime-local"
                    name="startDate"
                    :value="`${new Date(bid.startDate)
                        .toJSON()
                        .substring(0, 16)}`"
                    validation="required"
                    label="Date de début"
                    :classes="{
                        input: 'mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
                    }"
                />
                <FormKit
                    type="datetime-local"
                    name="endDate"
                    :value="`${new Date(bid.endDate)
                        .toJSON()
                        .substring(0, 16)}`"
                    validation="required"
                    label="Date de fin"
                    :classes="{
                        input: 'mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
                    }"
                />
                <FormKit
                    type="number"
                    min="0.01"
                    step="0.01"
                    name="startPrice"
                    :value="`${bid.startPrice}`"
                    validation="required"
                    label="Prix d'entrée"
                    disabled
                    :classes="{
                        input: 'bg-gray-400 mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
                    }"
                />
                <FormKit
                    type="number"
                    min="0.01"
                    step="0.01"
                    name="actualPrice"
                    :value="`${bid.actualPrice}`"
                    validation="required"
                    label="Prix actuel"
                    disabled
                    :classes="{
                        input: 'bg-gray-400 mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
                    }"
                />
                <FormKit
                    type="checkbox"
                    label="Terminée"
                    name="finished"
                    :value="bid.finished"
                    validation-visibility="dirty"
                    :classes="{
                        wrapper: 'flex justify-between flex-row-reverse',
                        input: 'h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded',
                    }"
                />
            </FormKit>
        </div>
    </div>
</template>

<script setup>
import { getBidById, updateBid } from "@/services/bid";
import { useRoute, useRouter } from "vue-router";
import { FormKit } from "@formkit/vue";
import { useToast } from "vue-toastification";
import { ref, onBeforeMount } from "vue";

const route = useRoute();
const router = useRouter();
const bids = ref([]);

onBeforeMount(() => {
    getBidById(route.params.id, bids);
    console.log(bids);
});
const submit = async (values) => {
    console.log(values);
    const toast = useToast();
    const response = await updateBid(bids.value[0].id, values);
    if (response.ok) {
        toast.success("Enchère modifiée avec succès !");
        await router.push("/bids");
    } else {
        toast.error(
            "Une erreur est survenue veuillez réessayer ultérieurement"
        );
    }
};
</script>
