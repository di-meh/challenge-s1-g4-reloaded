<template>
    <div className='row my-4 justify-center'>
        <ProductListItem :active="false" v-for="product in data.products" 
            :key="product.id" :product="product" />
    </div>
</template>

<script setup>
import ProductListItem from './ProductListItem.vue'
import { useShoppingStore } from '../Stores'
import ProductDetails from './ProductDetails.vue';

//get products from store
const data = useShoppingStore();

</script>

<style>
</style>