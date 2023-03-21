<template>
    <div
        class="flex w-full justify-center items-center h-full"
        v-for="bid in bids"
        :key="bid.id"
    >
        <div class="flex flex-col py-10 px-16 w-[500px]">
            <h2 class="text-3xl font-bold mb-6 text-center">
                Bid {{ bid.title }}
            </h2>
            <h3 class="text-xl font-bold mb-6 text-center">
                Prix de départ : {{ bid.startPrice }} €
            </h3>
            <h3 class="text-xl font-bold mb-6 text-center">
                Prix actuel : {{ bid.startPrice }} €
            </h3>
            <FormKit
                type="form"
                submit-label="Participer à une enchère"
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
                    min="0.01"
                    step="0.01"
                    name="startPrice"
                    validation="required"
                    label="Prix d'entrée"
                    :classes="{
                        input: 'mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
                    }"
                />
            </FormKit>
        </div>
    </div>
</template>

<script setup>
import { FormKit } from "@formkit/vue";
import { ref, onBeforeMount } from "vue";
import { getBidById } from "../../services/bid";
import { useRoute } from "vue-router";
//const userStore = useUserStore();
const route = useRoute();
const bids = ref([]);

onBeforeMount(() => {
    getBidById(route.params.id, bids);
});
</script>
