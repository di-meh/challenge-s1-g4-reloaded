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
        type: Number,
        required: true,
    },
    image: {
        type: String,
        required: true,
    },
    id: {
        type: Number,
        required: true,
    },
});
</script>
<template>
    <div>
        <div class="relative">
            <div class="relative h-72 w-full overflow-hidden rounded-lg">
                <img
                    :src="'/media/' + image"
                    alt=""
                    class="h-full w-full object-cover object-center"
                />
            </div>
            <div class="relative mt-4">
                <h3 class="text-sm font-medium">{{ title }}</h3>
            </div>
            <div
                class="absolute inset-x-0 top-0 flex h-72 items-end justify-end overflow-hidden rounded-lg p-4"
            >
                <div
                    aria-hidden="true"
                    class="absolute inset-x-0 bottom-0 h-36 bg-gradient-to-t from-black opacity-50"
                />
                <p class="relative text-lg font-semibold text-white">
                    {{ price }} €
                </p>
            </div>
        </div>
        <div class="mt-6">
            <RouterLink
                class="relative flex items-center justify-center rounded-md border border-transparent bg-gray-100 py-2 px-8 text-sm font-medium text-gray-900 hover:bg-gray-200"
                :to="'/annonces/' + id"
                >Voir l'annonce<span class="sr-only"
                    >, {{ title }}</span
                ></RouterLink
            >
        </div>
    </div>
</template>
