<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: true,
    },
    price: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="text-center space-y-3">
        <h1>{{ title }}</h1>
        <p>{{ description }}</p>
        <p>{{ price }}</p>
    </div>
</template>
