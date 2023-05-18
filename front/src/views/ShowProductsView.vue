<template>
    <!--
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

    -->

    <!--
  This example requires some changes to your config:
  
  ```
  // tailwind.config.js
  module.exports = {
    // ...
    plugins: [
      // ...
      require('@tailwindcss/aspect-ratio'),
    ],
  }
  ```
-->
    <div class="bg-white">
        <div
            class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8"
        >
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                Produits
            </h2>

            <div
                class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8"
            >
                <div v-for="(product, index) in products" :key="index">
                    <div class="group relative">
                        <div
                            class="min-h-80 aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80"
                        >
                            <img
                                :src="product.image"
                                alt="Front of men&#039;s Basic Tee in black."
                                class="h-full w-full object-cover object-center lg:h-full lg:w-full"
                            />
                        </div>
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700">
                                    <a href="#">
                                        <span
                                            aria-hidden="true"
                                            class="absolute inset-0"
                                        ></span>
                                        {{ product.name }}
                                    </a>
                                </h3>
                                <!-- <p class="mt-1 text-sm text-gray-500">Black</p> -->
                            </div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ product.cost }}
                            </p>
                        </div>
                    </div>
                    <button
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full"
                        v-on:click="addItemToCart(product)"
                    >
                        Ajouter au panier
                    </button>
                </div>

                <!-- More products... -->
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
                    cost: "0.99$",
                    image: "https://images.squarespace-cdn.com/content/v1/5a71fb0701002704ea478d9c/1641501731807-720ETHDN1VF1J5PMSJ6X/Screen+Shot+2022-01-06+at+1.40.26+PM.png?format=1500w",
                },
                {
                    name: "Cookies",
                    cost: "1.99$",
                    image: "https://assets.afcdn.com/recipe/20190529/93153_w1024h1024c1cx2220cy1728.jpg",
                },
            ],
        };
    },
    methods: {
        addItemToCart(product) {
            this.$emit("addItemToCart", product);
        },
    },
};
</script>
