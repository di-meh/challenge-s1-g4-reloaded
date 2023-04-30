<template>
    <div>
       <!--<h1>Your Cart</h1>
        <div class="products">  
            <div v-for="(product,index) in cart" :key="product.name">
                    {{ product.name }}
                    <img :src=product.image alt="">
                    <div>{{ product.cost }}</div>
                    <button v-on:click="removeItemFromCart(product)">Remove item</button>
            </div>
        </div> -->


        <div class="bg-white">
            <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Votre Panier</h2>

                <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                    
                    <div v-for="(product,index) in cart" :key="index">     
                        <div class="group relative">
                            <div class="min-h-80 aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
                            <img :src=product.image alt="Front of men&#039;s Basic Tee in black." class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                            </div>
                            <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700">
                                <a href="#">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    {{ product.name }}
                                </a>
                                </h3>
                                <!-- <p class="mt-1 text-sm text-gray-500">Black</p> -->
                            </div>
                            <p class="text-sm font-medium text-gray-900">{{ product.cost }}</p>
                            </div>
                        </div>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full" v-on:click="removeItemFromCart(product)">Supprimer du panier</button>

                </div>

                <!-- More products... -->
                </div>
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