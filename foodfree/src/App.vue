<template>
  <div id="app" class="container">
    <div class="row">
            <div class="col-md-6">
             <Menu :reload="reload"  @addToCart="addToCart" />
             </div>
             <div class="col-md-6">
              <Checkout  @reload-send="handleReload" :cart="cart" />
              </div>
    </div>
  </div>
</template>

<script>
import Menu from './components/Menu.vue';
import Checkout from './components/Checkout.vue';

export default {
  name: 'App',
  components: {
    Menu,
    Checkout
  },
  data() {
    return {
      cart: [],
      reload: false
    };
  },
  methods: {

    handleReload(msg) {
      console.log('handleReload', msg);
      this.reload = msg.reload;
    },
    addToCart(item) {
      this.cart.push(item);
    },
    removeItem(index) {
      this.cart.splice(index, 1);  // Eliminar el elemento en la posición index
    }
  }
};
</script>

<style>
.container {
  margin-top: auto;
  column-gap: 50px;
  padding: 50px;
  font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
}
.row{
  row-gap: 20px;
}
</style>
