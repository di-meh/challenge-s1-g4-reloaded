import { defineStore } from "pinia";
import Swal from "sweetalert2";

export const useShoppingStore = defineStore("shopping", {
  state: () => {
    return {
      products: [
        {
          id: 1,
          name: "Iphone 12",
          price: 700,
          image:
            "https://cdn.pixabay.com/photo/2016/11/20/08/33/camera-1842202__480.jpg",
          description:
            "Lorem ipsum, dolor sit amet consectetur adipisicing elit. Expedita omnis, tenetur, rerum tempore laborum reiciendis magnam incidunt doloribus dolorem facere impedit eos ullam quasi, voluptatibus ab earum. Maiores, id dicta.",
        },
        {
          id: 2,
          name: "Samsung s10",
          price: 400,
          image:
            "https://cdn.pixabay.com/photo/2016/03/27/19/43/samsung-1283938__340.jpg",
          description:
            "Lorem ipsum, dolor sit amet consectetur adipisicing elit. Expedita omnis, tenetur, rerum tempore laborum reiciendis magnam incidunt doloribus dolorem facere impedit eos ullam quasi, voluptatibus ab earum. Maiores, id dicta.",
        },
        {
          id: 3,
          name: "Samsung Tv",
          price: 1200,
          image:
            "https://cdn.pixabay.com/photo/2019/06/30/18/19/tv-4308538__480.jpg",
          description:
            "Lorem ipsum, dolor sit amet consectetur adipisicing elit. Expedita omnis, tenetur, rerum tempore laborum reiciendis magnam incidunt doloribus dolorem facere impedit eos ullam quasi, voluptatibus ab earum. Maiores, id dicta.",
        },
        {
          id: 4,
          name: "Huwawei Mate",
          price: 900,
          image:
            "https://cdn.pixabay.com/photo/2017/08/11/14/19/honor-2631271__340.jpg",
          description:
            "Lorem ipsum, dolor sit amet consectetur adipisicing elit. Expedita omnis, tenetur, rerum tempore laborum reiciendis magnam incidunt doloribus dolorem facere impedit eos ullam quasi, voluptatibus ab earum. Maiores, id dicta.",
        },
      ],
      cartItems: [],
    };
  },
  getters: {
    countCartItems() {
      return this.cartItems.length;
    },
    getCartItems() {
      return this.cartItems;
    },
  },
  actions: {
    addToCart(item) {
      let index = this.cartItems.findIndex((product) => product.id === item.id);
      if (index !== -1) {
        this.products[index].quantity += 1;
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Votre panier a été mis à jour",
          showConfirmButton: false,
          timer: 1500,
        });
      } else {
        item.quantity = 1;
        this.cartItems.push(item);
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Le produit a bien été rajouté au panier",
          showConfirmButton: false,
          timer: 1500,
        });
      }
    },
    incrementQ(item) {
      let index = this.cartItems.findIndex((product) => product.id === item.id);
      if (index !== -1) {
        this.cartItems[index].quantity += 1;
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Votre panier a été mis à jour",
          showConfirmButton: false,
          timer: 1500,
        });
      }
    },
    decrementQ(item) {
      let index = this.cartItems.findIndex((product) => product.id === item.id);
      if (index !== -1) {
        this.cartItems[index].quantity -= 1;
        if (this.cartItems[index].quantity === 0) {
          this.cartItems = this.cartItems.filter(
            (product) => product.id !== item.id
          );
        }
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Votre panier a été mis à jour",
          showConfirmButton: false,
          timer: 1500,
        });
      }
    },
    removeFromCart(item) {
      this.cartItems = this.cartItems.filter(
        (product) => product.id !== item.id
      );
      Swal.fire({
        position: "top-end",
        icon: "success",
        title: "Le produit a bien été supprimé du panier",
        showConfirmButton: false,
        timer: 1500,
      });
    },
  },
});
