<template>
    <h1>Liste des enchères</h1>
    <!-- <div v-for="bid in bids">
        {{ bid.id }}
        {{ bid.creator }}
        {{ bid.startPrice }}
        {{ bid.actualPrice }}
        {{ bid.startDate }}
        {{ bid.endDate }}
        {{ bid.endDate }}
    </div> -->
</template>

<script setup>
import { ref, onBeforeMount } from "vue";
import { getAllBids } from "../../services/bid";

const bids = ref([]);

onBeforeMount(() => getAllBids(bids));
</script>
