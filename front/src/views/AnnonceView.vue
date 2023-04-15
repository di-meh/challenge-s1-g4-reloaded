<template>
        <p v-if="items.length <= 0">Aucunes annonces !</p>
        <div class="text-white">
            <div class="mx-auto max-w-2xl py-16 px-4 sm:py-24 sm:px-6 lg:max-w-7xl lg:px-8">
              <h2 class="text-xl font-bold">Annonces</h2>
        
              <div class="mt-8 grid grid-cols-1 gap-y-12 sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-8">
                
                    <AnnonceCard
                    v-for="item in items"
                    :key="item.id"
                    :title="item.title"
                    :id="item.id"
                    :description="item.description"
                    :price="item.price"
                    :image="item.images[0].filePath"
                    style="cursor: pointer"
                />
                </div>
          </div>
        </div>
       
</template>

<script setup>
    import AnnonceCard from "@/components/AnnonceCard.vue";
    import { ref, onBeforeMount } from "vue";
    import { ENTRYPOINT } from "../../config/entrypoint";
    import { useRouter } from "vue-router";

    const router = useRouter();
    let items = ref([]);

    onBeforeMount(async () => {
        const response = await fetch(`${ENTRYPOINT}/annonces`);
        const data = await response.json();
        items.value = data["hydra:member"];
    });
</script>