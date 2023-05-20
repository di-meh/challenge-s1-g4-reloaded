<template>
    <div class="text-white">
        <div
            class="mx-auto max-w-2xl py-16 px-4 sm:py-24 sm:px-6 lg:max-w-7xl lg:px-8"
        >
            <div class="lg:grid lg:grid-cols-2 lg:items-start lg:gap-x-8">
                <!-- Image gallery -->
                <TabGroup as="div" class="flex flex-col-reverse">
                    <!-- Image selector -->
                    <div
                        class="mx-auto mt-6 hidden w-full max-w-2xl sm:block lg:max-w-none"
                    >
                        <TabList class="grid grid-cols-4 gap-6">
                            <Tab
                                class="click relative flex h-24 cursor-pointer items-center justify-center rounded-md bg-white text-sm font-medium uppercase text-gray-900 hover:bg-gray-50 focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-offset-4"
                                v-slot="{ selected }"
                            >
                                <span class="sr-only">{{ annonce.title }}</span>
                                <span
                                    class="absolute inset-0 overflow-hidden rounded-md"
                                >
                                    <img
                                        :src="
                                            '/media/' +
                                            annonce.images[0].filePath
                                        "
                                        alt=""
                                        class="h-full w-full object-cover object-center"
                                    />
                                </span>
                                <span
                                    :class="[
                                        selected
                                            ? 'ring-indigo-500'
                                            : 'ring-transparent',
                                        'pointer-events-none absolute inset-0 rounded-md ring-2 ring-offset-2',
                                    ]"
                                    aria-hidden="true"
                                />
                            </Tab>
                            <!-- Fonctionne mais erreur console -->
                            <Tab
                                v-for="image in annonce.images.slice(1)"
                                :key="image.id"
                                class="click relative flex h-24 cursor-pointer items-center justify-center rounded-md bg-white text-sm font-medium uppercase text-gray-900 hover:bg-gray-50 focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-offset-4"
                                v-slot="{ selected }"
                            >
                                <span class="sr-only">{{ annonce.title }}</span>
                                <span
                                    class="absolute inset-0 overflow-hidden rounded-md"
                                >
                                    <img
                                        :src="'/media/' + image.filePath"
                                        alt=""
                                        class="h-full w-full object-cover object-center"
                                    />
                                </span>
                                <span
                                    :class="[
                                        selected
                                            ? 'ring-indigo-500'
                                            : 'ring-transparent',
                                        'pointer-events-none absolute inset-0 rounded-md ring-2 ring-offset-2',
                                    ]"
                                    aria-hidden="true"
                                />
                            </Tab>
                        </TabList>
                    </div>
                    <TabPanels class="aspect-w-1 aspect-h-1 w-full">
                        <TabPanel
                            v-for="image in annonce.images"
                            :key="image.id"
                        >
                            <img
                                :src="'/media/' + image.filePath"
                                :alt="image.alt"
                                class="h-full w-full object-cover object-center sm:rounded-lg"
                            />
                        </TabPanel>
                    </TabPanels>
                </TabGroup>

                <!-- Product info -->
                <div class="mt-10 px-4 sm:mt-16 sm:px-0 lg:mt-0">
                    <h1 class="text-3xl font-bold tracking-tight">
                        {{ annonce.title }}
                    </h1>

                    <div class="mt-3">
                        <h2 class="sr-only">Product information</h2>
                        <p class="text-3xl tracking-tight">
                            {{ annonce.price }} €
                        </p>
                    </div>

                    <div class="mt-6">
                        <h3 class="sr-only">Description</h3>

                        <div
                            class="space-y-6 text-base"
                            v-html="annonce.description"
                        />
                    </div>

                    <div class="mt-6">
                        <div class="sm:flex-col1 mt-10 flex">
                            <button
                                v-if="isOwner"
                                @click="deleteAnnonce"
                                class="flex max-w-xs flex-1 items-center justify-center rounded-md border border-transparent bg-red-600 py-3 px-8 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-50 sm:w-full"
                            >
                                Supprimer votre annonce
                            </button>
                            <button
                                v-else
                                @click="buy"
                                class="flex max-w-xs flex-1 items-center justify-center rounded-md border border-transparent bg-indigo-600 py-3 px-8 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50 sm:w-full"
                            >
                                Acheter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onBeforeMount, reactive, toRaw } from "vue";
import { Tab, TabGroup, TabList, TabPanel, TabPanels } from "@headlessui/vue";
import { ENTRYPOINT } from "../../config/entrypoint";
import { useRoute } from "vue-router";
import jwtDecode from "jwt-decode";
import { useCookies } from "@vueuse/integrations/useCookies";
import { loadStripe } from "@stripe/stripe-js";
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";

const route = useRoute();
const id = route.params.id;
let annonce = ref([]);
const publishableKey = import.meta.env.VITE_STRIPE_PUBLIC_KEY;
const stripePromise = loadStripe(publishableKey);
const successURL = `https://${window.location.host}/payment/success`;
const cancelURL = `https://${window.location.host}/payment/cancel`;
const cookies = useCookies();
const decodedToken = jwtDecode(cookies.get("token"));
const isOwner = ref(false);
const toast = useToast();
const router = useRouter();

const lineItems = reactive([
    {
        price: "",
        quantity: 1,
    },
]);

onBeforeMount(async () => {
    const annonces = await fetch(`${ENTRYPOINT}/annonces/${id}`, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
        },
    });
    const data = await annonces.json();
    annonce.value = data;
    lineItems[0].price = data["stripe_price_id"];

    if (data["annonceOwner"]["email"] === decodedToken.email) {
        isOwner.value = true;
    }
});

const buy = async () => {
    if (confirm("Voulez vous vraiment acheter cette annonce ?")) {
        const stripe = await stripePromise;
        const { error } = await stripe.redirectToCheckout({
            mode: "payment",
            lineItems: lineItems,
            successUrl: successURL,
            cancelUrl: cancelURL,
            customerEmail: decodedToken.email,
        });
        if (error) {
            console.error("Error:", error);
        }
    }
};

const deleteAnnonce = async () => {
    if (confirm("Voulez vous vraiment supprimer votre annonce ?")) {
        for (const mediaObjects of annonce.value.images) {
            await fetch(
                `${ENTRYPOINT}/media_objects/${toRaw(mediaObjects).id}`,
                {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${cookies.get("token")}`,
                    },
                }
            );
        }
        const deleteAnnonceFetch = await fetch(`${ENTRYPOINT}/annonces/${id}`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${cookies.get("token")}`,
            },
        });
        if (deleteAnnonceFetch.ok) {
            await router.push("/");
            toast.success("Votre annonce a bien été supprimée");
        } else {
            toast.error("Une erreur est survenue.");
        }
    }
};
</script>
