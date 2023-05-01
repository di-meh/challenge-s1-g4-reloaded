<template>
    <div>
        <ProductDescriptionDrawer 
        :product="product"
        :active="active.product_drawer"
        v-on:close-product-drawer="closeProductDrawer()"
        />


        <div class="products-cards-container flex justify-center flex-wrap">
            <ProductSummaryCard
            v-for="product in items"
            :key="product.id"
            :product="product"
            v-on:view-product="viewProduct($event)"/>
        </div>
    </div>
</template>

<script>
    import items from "../data/items.js";
    import ProductSummaryCard from "../components/products/ProductSummaryCard.vue"
    import ProductDescriptionDrawer from '../components/products/ProductDescriptionDrawer.vue'
    export default {
        name: "allproducts",
        components: {
            ProductSummaryCard, ProductDescriptionDrawer
        },
        data() {
            return {
                items: items,
                product: null,
                active: {
                    product_drawer: false
                }
            }
        },
        methods: {
            viewProduct(product) {
                console.log(this.active.product_drawer)
                this.product = product
                this.active.product_drawer = true
                console.log(this.product)
                console.log(this.active.product_drawer)
            },
            closeProductDrawer() {
                this.active.product_drawer = false
            }
        }
    }
</script>


