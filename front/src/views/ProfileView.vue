<template>

    <form @submit.prevent="editUser(idUser)" class="flex flex-col gap-5">
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
  <div class="px-4 py-5 sm:px-6">
    <h3 class="text-lg font-medium leading-6 text-gray-900">Profile</h3>
    <p class="mt-1 max-w-2xl text-sm text-gray-500">Profile information</p>
  </div>
  <div class="border-t border-gray-200">
    <dl>
      <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
        <dt class="text-sm font-medium text-gray-500">Full name</dt>
        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{name}}</dd>
      </div>

      <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
        <dt class="text-sm font-medium text-gray-500">Email address</dt>
        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{email}}</dd>
      </div>
      <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
        <dt class="text-sm font-medium text-gray-500">Role</dt>
        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ roles }}</dd>
      </div>


    </dl>
  </div>
  <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Modifier</button>
          </div>
</div>
</form>
</template>

<script setup>
    import { ENTRYPOINT } from "../../config/entrypoint";
    import { ref } from "vue";
    import { useRoute } from 'vue-router';

    const route = useRoute(); 
    const email = ref(null)
    const name = ref(null)
    const roles = ref(null)
    const idUser = ref(null)
    

    const getUser = async () => {
        idUser.value = route.params.id;
        const response = await fetch(ENTRYPOINT + `/users/${idUser.value}`, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
        },
        }).then(res => res.json());
        if (response) {
            email.value = response.email;
            name.value = response.name;
            if(response.roles = "['ROLE_USER']") {
                roles.value = "User"
            }

        } else {
            throw new Error("Erreur");
        }
    };
    getUser();
    
    const editUser = (id) => {
        window.location.href = `/update-user/${id}`;
    }
    

    

</script>
