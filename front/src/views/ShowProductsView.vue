<template>
    <div>
        <h1>Products</h1>
        <div class="products">  
            <div v-for="(product,index) in products" :key="index">                      
                    {{ product.name }}
                    <img :src=product.image alt="">
                    <div>{{ product.cost }}</div>
                    <button v-on:click="addItemToCart(product)">Add to Cart</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: () => {
            return {
                products: [
                    {
                        name: "Hair Brush",
                        cost: '0.99$',
                        image: 'https://images.squarespace-cdn.com/content/v1/5a71fb0701002704ea478d9c/1641501731807-720ETHDN1VF1J5PMSJ6X/Screen+Shot+2022-01-06+at+1.40.26+PM.png?format=1500w'
                    },
                    {
                        name: "Cookies",
                        cost: '1.99$',
                        image: 'https://assets.afcdn.com/recipe/20190529/93153_w1024h1024c1cx2220cy1728.jpg'
                    }
                ]
            }
        },
        methods: {
            addItemToCart(product) {
                this.$emit('addItemToCart', product);
            }
        }
    }
</script>