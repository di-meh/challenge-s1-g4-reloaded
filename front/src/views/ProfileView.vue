<template>
  <div  class="flex flex-col gap-5">
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Profile</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Profile information</p>
      </div>
      <div class="border-t border-gray-200">
        <dl>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Username</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{name}}</dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Email address</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{email}}</dd>
          </div>
        </dl>
      </div>
      <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
        <RouterLink to="/update-user">
          <button type="button" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Modifier</button>
        </RouterLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from "vue-router";
import { useUserStore } from "@/store/user";
import { useToast } from "vue-toastification";
import { ref,onBeforeMount } from "vue";
import { useCookies } from "@vueuse/integrations/useCookies";


const cookies = useCookies();
const userStore = useUserStore();
const toast = useToast();
const userId = JSON.parse(localStorage.getItem('user')).id
const email = ref(null)
const name = ref(null)
const roles = ref(null)
const idUser = ref(null)
const router = useRouter();
const getUser = async () => {
  const response = await userStore.getUser();
  if(response) {
    email.value = response.email;
    name.value = response.username;

  } else {
    throw new Error("Erreur");
  }
}

onBeforeMount(() => {
  getUser();
})
    

</script>
