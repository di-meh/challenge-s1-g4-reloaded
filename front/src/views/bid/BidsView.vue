<template>
    <div class="flex w-full justify-center items-center h-full">
        <div class="w-full max-w-md px-2 py-16 sm:px-0">
            <TabGroup>
                <TabList class="flex space-x-1 rounded-xl bg-blue-900/20 p-1">
                    <Tab
                        v-for="category in Object.keys(categories)"
                        as="template"
                        :key="category"
                        v-slot="{ selected }"
                    >
                        <button
                            :class="[
                                'w-full rounded-lg py-2.5 text-sm font-medium leading-5 text-blue-700',
                                'ring-white ring-opacity-60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
                                selected
                                    ? 'bg-white shadow'
                                    : 'text-blue-100 hover:bg-white/[0.12] hover:text-white',
                            ]"
                            v-on:click="handleClickCategory(category)"
                        >
                            {{ category }}
                        </button>
                    </Tab>
                </TabList>

                <TabPanels class="mt-2">
                    <TabPanel
                        :static="true"
                        :class="[
                            'rounded-xl p-3',
                            'ring-white ring-opacity-60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
                        ]"
                    >
                        <ul>
                            <li
                                v-for="bid in bids"
                                :key="bid.id"
                                class="relative rounded-md p-3 hover:bg-gray-100 hover:text-black"
                            >
                                <h3 class="text-xl font-medium leading-5">
                                    {{ bid.title }}
                                </h3>
                                <h4 class="text-sm font-medium leading-5">
                                    Prix de départ : {{ bid.startPrice }} €
                                </h4>
                                <h4 class="text-sm">
                                    Prix actuel : {{ bid.actualPrice }} €
                                </h4>
                                <ul
                                    class="mt-1 flex space-x-1 text-xs font-normal leading-4 text-gray-500"
                                >
                                    <li>
                                        {{
                                            new Date(bid.startDate)
                                                .toUTCString()
                                                .substring(25, 4)
                                        }}
                                    </li>

                                    <li>&middot;</li>
                                    <li>
                                        {{
                                            new Date(bid.endDate)
                                                .toUTCString()
                                                .substring(25, 4)
                                        }}
                                    </li>
                                </ul>

                                <a
                                    href="#"
                                    :class="[
                                        'absolute inset-0 rounded-md',
                                        'ring-blue-400 focus:outline-none focus:ring-2',
                                    ]"
                                />
                            </li>
                        </ul>
                    </TabPanel>
                </TabPanels>
            </TabGroup>
        </div>
    </div>
</template>

<script setup>
import { ref, onBeforeMount } from "vue";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from "@headlessui/vue";

import { getBidsByFinished } from "../../services/bid";

const bids = ref([]);
const selectedTab = ref(0);
const categories = ref({
    All: [],
    Finished: [],
    "Not Finished": [],
});

onBeforeMount(() => {
    getBidsByFinished("All", bids);
});

const handleClickCategory = (category) => {
    selectedTab.value = 0;
    getBidsByFinished(category, bids);
};
</script>
