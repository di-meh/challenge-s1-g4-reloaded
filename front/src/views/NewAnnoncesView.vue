<template>
    <h1>Ajout d'une annonce</h1>
    <form @submit.prevent="addNewAnnonce($event)">
        <div>
            <label for="title" class="block text-sm font-medium text-white">Titre</label>
            <div class="mt-1">
                <input type="text" name="title" id="title"
                    class="block w-full rounded-md border-gray-300 text-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
            </div>
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-white">Description</label>
            <div class="mt-1">
                <textarea name="description" id="description"
                    class="block w-full rounded-md border-gray-300 text-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
            </div>
        </div>
        <div>
            <label for="price" class="block text-sm font-medium text-white">Prix</label>
            <div class="mt-1">
                <input type="number" name="price" id="price" min="1"
                    class="block w-full rounded-md border-gray-300 text-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
            </div>
        </div>
        <div>
            <label for="pictures" class="block text-sm font-medium text-white">images</label>
            <div class="mt-1">
                <input type="file" name="file" id="file"
                    class="block w-full rounded-md border-gray-300 text-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    @change="uploadFile($event)" multiple />
            </div>
        </div>
        <button type="submit"
            class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-3 py-2 text-sm font-medium leading-4 text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Register
        </button>
    </form>
</template>

<script setup>
import { ENTRYPOINT } from "../../config/entrypoint";
import { useRouter } from "vue-router";
import { ref } from "vue";
import { useCookies } from "@vueuse/integrations/useCookies";
const cookies = useCookies();
const router = useRouter();
let token = cookies.get("token");
if (!token) {
    router.push("/login");
}
const imagesFiles = ref(new FormData());
async function uploadFile(event) {
    if (event.target.files.length > 5) {
        alert("Vous ne pouvez pas ajouter plus de 5 images");
        return;
    }
    const formData = new FormData();
    for (let i = 0; i < event.target.files.length; i++) {
        formData.append("file", event.target.files[i]);
    }
    imagesFiles.value = formData;
}

async function addNewAnnonce(event) {
  let mediaResponse = [];
  for (let i = 0; i < imagesFiles.value.getAll("file").length; i++) {
    const formData = new FormData();
    formData.append("file", imagesFiles.value.getAll("file")[i]);

    let response = await fetch(ENTRYPOINT + "/media_objects", {
      method: "POST",
      headers: {
        Authorization: "Bearer " + token,
      },
      body: formData,
    }).then((response) => response.json());
    mediaResponse.push(response);
  }

  let annonceResponse = await fetch(ENTRYPOINT + "/annonces", {
    method: "POST",
    headers: {
      Authorization: "Bearer " + token,
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      title: event.target.title.value,
      description: event.target.description.value,
      price: event.target.price.valueAsNumber,
      images: mediaResponse.map((item) => item["@id"])
    }),
  }).then((response) => response.json()).then(console.log('Annonce créée'));
}
</script>