<template>
    <div>
        <h1>Your Cart</h1>
        <div class="products">  
            <div v-for="(product,index) in cart" :key="product.name">
                    {{ product.name }}
                    <img :src=product.image alt="">
                    <div>{{ product.cost }}</div>
                    <button v-on:click="removeItemFromCart(product)">Remove item</button>
            </div>
        </div>
    </div>


</template>

<script>
    export default {
        props: ["cart"],
        methods: {
            removeItemFromCart(product) {
                this.$emit('removeItemFromCart', product);
            }
        }
    }
</script>