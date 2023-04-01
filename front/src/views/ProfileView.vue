<template>
    <div class="flex flex-col gap-5 mx-auto w-1/2 mt-6">
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">
                    Profil
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Informations</p>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div
                        class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                    >
                        <dt class="text-sm font-medium text-gray-500">
                            Username
                        </dt>
                        <dd
                            class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{ name }}
                        </dd>
                    </div>
                    <div
                        class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                    >
                        <dt class="text-sm font-medium text-gray-500">
                            Adresse mail
                        </dt>
                        <dd
                            class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{ email }}
                        </dd>
                    </div>
                </dl>
            </div>
            <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                <RouterLink to="/update-user">
                    <button
                        type="button"
                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Modifier
                    </button>
                </RouterLink>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useUserStore } from "@/store/user";
import { useToast } from "vue-toastification";
import { ref, onBeforeMount } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const userStore = useUserStore();
const toast = useToast();
const email = ref(null);
const name = ref(null);
const getUser = async () => {
    const response = await userStore.getUser();
    if (response.ok) {
        const json = await response.json();
        email.value = json.email;
        name.value = json.username;
    } else {
        toast.error("Une erreur est survenue");
        await router.push("/");
    }
};

onBeforeMount(() => {
    getUser();
});
</script>
