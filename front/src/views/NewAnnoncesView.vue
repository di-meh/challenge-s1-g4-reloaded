<template>
    <h1 class="text-center text-2xl font-bold pt-10">Ajout d'une annonce</h1>
    <div class="flex w-full justify-center items-center h-full">
        
        <FormKit type="form" @submit="addNewAnnonce($event)" submit-label="Créer"
            form-class="w-1/3"
            :submit-attrs="{ inputClass: 'w-full rounded-md bg-indigo-600 py-2.5 px-3.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 text-center', outerClass: 'pt-4'}">
            <FormKit :classes="{ input: 'mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6', wrapper: 'mb-2' }" type="text" name="title" label="Titre" placeholder="Titre"
                validation="required" />
            <FormKit :classes="{ input: 'mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6', wrapper: 'mb-2' }" type="textarea" name="description" label="Description"
                placeholder="Description" validation="required" />
            <FormKit :classes="{ input: 'mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6', wrapper: 'mb-2' }" type="number" name="price" label="Prix" placeholder="Prix"
                validation="required" />
            <FormKit :classes="{input: ''}" type="file" label="Images" multiple="true" @change="uploadFile($event)" accept=".webp,.jpg,.png,.jpeg"/>
        </FormKit>
    </div>
</template>

<script setup>
import { FormKit } from "@formkit/vue";
import { ENTRYPOINT } from "../../config/entrypoint";
import { useRouter } from "vue-router";
import { ref } from "vue";
import { useCookies } from "@vueuse/integrations/useCookies";
import { useToast } from "vue-toastification";
const toast = useToast();
const cookies = useCookies();
const router = useRouter();
let token = cookies.get("token");
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
            title: event.title,
            description: event.description,
            price: parseFloat(event.price),
            images: mediaResponse.map((item) => item["@id"])
        }),
    });

    if (annonceResponse.status === 201) {
        toast.success("Annonce créée avec succès");
        router.push({ name: "Home" });
    } else {
        toast.error("Une erreur est survenue");
    }
}
</script>