<template>
    <div>
        <header>
            <button v-on:click="navigateTo('products')">View Products</button>
                {{cart.length}} in cart
           <button v-on:click="navigateTo('cart')">View Cart</button>
        </header>

        <div v-if="page === 'cart'">
        <h1>Your Cart</h1>
            <div class="products">  
                <div v-for="product in cart" :key="product.name">
                        {{ product.name }}
                        <img :src=product.image alt="">
                        <div>{{ product.cost }}</div>
                        <button v-on:click="removeItemFromCart(product)">Remove item</button>
                </div>
            </div>
        </div>



        <div v-if="page === 'products'">
            <h1>Products</h1>
                <div class="products">  
                    <div v-for="product in products" :key="product.name">
                        
                            {{ product.name }}
                            <img :src=product.image alt="">
                            <div>{{ product.cost }}</div>
                            <button v-on:click="addItemToCart(product)">Add to Cart</button>
                    </div>
                </div>
        </div>

    </div>
</template>


<script>
    export default {
        name: "App",
        data: () => {
            
            return {
                page: "cart",
                cart: [],
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
                this.cart.push(product)
            },
            navigateTo(page) {
                this.page = page;
            },
            removeItemFromCart(product) {
                this.cart.splice(this.cart.indexOf(product))
            }
        },
        components: {}
    }
</script>

<style scoped>
    .products {
        width:300px;
        display: flex;
        justify-content: space-between;
    }

    header {
        height: 60px;
        background-color: #eee;
        box-shadow: 2px 2px 5px #999;
        text-align: right;
        padding-top: 20px;
    }
    body {
        margin: 0;
    }

    header button {
        background-color: green;
    }

</style>