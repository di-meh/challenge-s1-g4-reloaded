<template>

    <h1>ADD ANNONCE</h1>
    <form @submit.prevent="createAnnonce($event)">
        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" class="form-control" id="title" aria-describedby="title">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" class="form-control" id="description">
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Prix</label>
            <input type="number" class="form-control" id="price">
        </div>
        <div class="mb-3">
            <label for="images" class="form-label">Images</label>
            <input type="file" class="form-control" id="images" multiple >
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</template>


<script setup>
    import jwtDecode from "jwt-decode";

    import { ref, onBeforeMount } from "vue";
    import { useCookies } from "@vueuse/integrations/useCookies";
    import { ENTRYPOINT } from "../../config/entrypoint";
    const cookies = useCookies();

    let token = cookies.get("token");
    let user = jwtDecode(token);

    let imgs = [];

    async function createAnnonce (event) {
        // const formData = new FormData();
        const title = event.target.title.value;
        const description = event.target.description.value;
        const price = parseInt(event.target.price.value);
        const images = event.target.images.files;

        for (let i = 0; i < images.length; i++) {
            imgs.push(images[i]);
        }
        // formData.append("file", event.target.images.files[0]);
        // imgs.value = formData;

        console.log(imgs);
        const annonceResponse = await fetch(ENTRYPOINT + "/annonces", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                title: title,
                description: description,
                price: price,
                images: imgs[0]
            }),
        });
        if (annonceResponse.ok) {
            console.log('ok');
        }

        // TODO FIX
        // le path est null car les images ne sont pas envoyées dans le body
     

    }
</script>

<style>
    input {
        color:black !important;
    }
</style>