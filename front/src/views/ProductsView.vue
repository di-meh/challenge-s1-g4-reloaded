<template>
    <div>
        <header>
           <!-- <button v-on:click="navigateTo('products')" class="h-10 px-5 text-indigo-100 transition-colors duration-150 bg-indigo-700 rounded-lg focus:shadow-outline hover:bg-indigo-800">
                <span class="mr-2" >Voir les produits</span>
            </button>
           -->

           <button v-on:click="navigateTo('cart')" class="h-10 px-5 text-indigo-100 transition-colors duration-150 bg-indigo-700 rounded-lg focus:shadow-outline hover:bg-indigo-800">
                <span class="mr-2" >Voir votre Panier</span>
                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{cart.length}}</span>
            </button>
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
        padding-top: 10px;
    }
    body {
        margin: 0;
    }



</style>