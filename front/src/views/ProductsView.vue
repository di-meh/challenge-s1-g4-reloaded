<template>
    <div>
        <header>
            <button v-on:click="navigateTo('products')">View Products</button>
                {{cart.length}} in cart
           <button v-on:click="navigateTo('cart')">View Cart</button>
        </header>

        <div v-if="page === 'cart'">
            <ShowCartView :cart="cart" v-on:removeItemFromCart="removeItemFromCart"/>
        </div>

        <div v-if="page === 'products'">
            <ShowProductsView v-on:addItemToCart="addItemToCart"/>
        </div>

    </div>
</template>


<script>
import ShowCartView from './ShowCartView.vue';
import ShowProductsView from './ShowProductsView.vue'
export default {
        name: "App",
        data: () => {
            
            return {
                page: "products",
                cart: [],
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
                this.cart.splice(this.cart.indexOf(product),1)
            }
        },
        components: { ShowProductsView, ShowCartView }
    }
</script>

<style>
    .products {
        width:300px;
        display: flex;
        justify-content: space-between;
    }

</style>

<style scoped>
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