<template>
    <div
        v-if="active"
        class="drawer-background"
        :class="{ show: active }"
        @click="$emit('close-product-drawer')"
    >
        <div class="drawer" :class="{ show: active }">
            <div class="drawer-close" @click="$emit('close-product-drawer')">
                X
            </div>
            <div v-if="product" class="product-details">
                <h3 class="text-center">{{ product.name }}</h3>
                <p class="description">{{ product.description }}</p>
                <h3 class="text-center">${{ product.price.toFixed(2) }}</h3>

                <div class="cart-total" v-if="product_total">
                    <h3>In Cart</h3>
                    <h4>{{ product_total }}</h4>
                </div>

                <div class="button-container">
                    <button class="remove">Remove</button>
                    <button class="add">Add</button>
                </div>
            </div>
        </div>
    </div>

    <!---->

    <div v-if="active"
        class="relative z-10"
        aria-labelledby="slide-over-title"
        role="dialog"
        aria-modal="true"
    >
        <div
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
        ></div>

        <div class="fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div
                    class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10"
                >
                    <div class="pointer-events-auto w-screen max-w-md">
                        <div
                            class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl"
                        >
                            <div
                                class="flex-1 overflow-y-auto px-4 py-6 sm:px-6"
                            >
                                <div class="flex items-start justify-between">
                                    <h2
                                        class="text-lg font-medium text-gray-900"
                                        id="slide-over-title"
                                    >
                                        Product Info
                                    </h2>
                                    <div class="ml-3 flex h-7 items-center">
                                        <button @click="$emit('close-product-drawer')"
                                            type="button"
                                            class="-m-2 p-2 text-gray-400 hover:text-gray-500"
                                        >
                                            <span class="sr-only"
                                                >Close panel</span
                                            >
                                            <svg
                                                class="h-6 w-6"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                                aria-hidden="true"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-8">
                                    <div class="flow-root">
                                        <ul
                                            role="list"
                                            class="-my-6 divide-y divide-gray-200"
                                        >
                                            <li class="flex py-6">
                                                <div
                                                    class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200"
                                                >
                                                    <img
                                                        src="https://tailwindui.com/img/ecommerce-images/shopping-cart-page-04-product-01.jpg"
                                                        alt="Salmon orange fabric pouch with match zipper, gray zipper pull, and adjustable hip belt."
                                                        class="h-full w-full object-cover object-center"
                                                    />
                                                </div>

                                                <div
                                                    class="ml-4 flex flex-1 flex-col"
                                                >
                                                    <div>
                                                        <div
                                                            class="flex justify-between text-base font-medium text-gray-900"
                                                        >
                                                            <h3>
                                                                <a href="#"
                                                                    >{{ product.name }}</a
                                                                >
                                                            </h3>
                                                            <p class="ml-4">
                                                                ${{ product.price.toFixed(2) }}
                                                            </p>
                                                        </div>
                                                        <p
                                                            class="mt-1 text-sm text-gray-500"
                                                        >
                                                        {{ product.description }}
                                                        </p>
                                                    </div>
                                                    <div
                                                        class="flex flex-1 items-end justify-between text-sm"
                                                    >
                                                    <!--
                                                        <p
                                                            class="text-gray-500"
                                                        >
                                                            Qty 1
                                                        </p>
                                                    

                                                        <div class="flex">
                                                            <button
                                                                type="button"
                                                                class="font-medium text-indigo-600 hover:text-indigo-500"
                                                            >
                                                                Remove
                                                            </button>
                                                        </div>
                                                    -->
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="border-t border-gray-200 px-4 py-6 sm:px-6"
                            >
                                <div
                                    class="flex justify-between text-base font-medium text-gray-900"
                                >
                                    <p>In Cart</p>
                                    <p>5</p>
                                </div>
                                

                                <!--
                                <p class="mt-0.5 text-sm text-gray-500">
                                    Shipping and taxes calculated at checkout.
                                </p>
                                -->
                                <div class="mt-6">
                                    <a
                                        href="#"
                                        class="flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700"
                                        >Remove</a
                                    >
                                </div>

                                <div class="mt-6">
                                    <a
                                        href="#"
                                        class="flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700"
                                        >Add</a
                                    >
                                </div>

                                <!--
                                <div
                                    class="mt-6 flex justify-center text-center text-sm text-gray-500"
                                >
                                    <p>
                                        or
                                        <button
                                            type="button"
                                            class="font-medium text-indigo-600 hover:text-indigo-500"
                                        >
                                            Continue Shopping
                                            <span aria-hidden="true">
                                                &rarr;</span
                                            >
                                        </button>
                                    </p>
                                </div>
                                -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["product", "active"],
    computed: {
        product_total() {
            return 5;
        },
    },
};
</script>

<style>
/* .drawer-background {
    width: 100%;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background-color: rgba(124, 124, 124, 0.55);
    z-index: 100;
    display: none;
    transition: 0.5s;
}

.drawer-background .show {
    display: block;
}

.drawer {
    width: 95vw;
    height: 100vh;
    background-color: black;
    position: fixed;
    top: 0;
    left: -105vh;
    padding: 15px;
    transition: left 0.5s;
    z-index: 101;
    overflow-y: scroll;
}

.drawer .show {
    left: 0;
}

.drawer-close {
    font-size: 1.5rem;
    padding: 5px;
    border-radius: 5px;
    right: 10px;
    border: 2px solid gray;
    color: gray;
    width: 15px;
    float: right;
    cursor: pointer;
}

.drawer-close:hover {
    background-color: lightgray;
}

.product-details {
    display: flex;
    justify-content: center;
    flex-direction: column;
}

.product-details p.description {
    padding: 20px;
    line-height: 1.5rem;
}

.product-details .button-container .button {
    width: 150px;
    border: none;
    padding: 10px;
    border-radius: 5px;
    margin: 0 5px 50px 5px;
    cursor: pointer;
} */
</style>
