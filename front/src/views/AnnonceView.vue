<template>
    <h1>Annonces</h1>
    <div class="grid grid-cols-3 gap-4">
        <p v-if="items.length <= 0">Aucunes annonces !</p>
        <AnnonceCard
            v-for="item in items"
            :key="item.id"
            :title="item.title"
            :description="item.description"
            :price="item.price"
            :pictures="item.pictures"
        />
    </div>
</template>

<script setup>
    import AnnonceCard from "@/components/AnnonceCard.vue";
    import { ref, onBeforeMount } from "vue";
    import { ENTRYPOINT } from "../../config/entrypoint";
    let items = ref([]);

    onBeforeMount(async () => {
        const response = await fetch(`${ENTRYPOINT}/annonces`);
        const data = await response.json();
        items.value = data["hydra:member"];
        console.log(items.value);
    });


   

</script>