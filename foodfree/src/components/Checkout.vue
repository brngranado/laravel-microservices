<template>
  <div class="checkout">
    <h1>Bienvenido, estamos listos para tomar tu orden</h1>
    <hr>
    <PaymentForm @reload-send-to-parent="handleReload" :amount="grandTotal" />
  </div>
</template>

<script>
import Customer from './Customer.vue';
import PaymentForm from './PaymentForm.vue'

export default {
  name: 'Checkout',
  props: {
    cart: {
      type: Array,
      required: true
    },
    reload: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    groupedCart() {
      const grouped = [];
      this.cart.forEach(item => {
        const existingItem = grouped.find(i => i.name === item.name && i.price === item.price);
        if (existingItem) {
          existingItem.quantity++;
        } else {
          grouped.push({ ...item, quantity: 1 });
        }
      });
      return grouped;
    },
    total() {
      return this.groupedCart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    },
    tax() {
      return this.total * 0.065;
    },
    totalDelivery() {
        return (this.total * 0.03) + this.deliveryFee;
    },
    grandTotal() {
      return this.tax + this.total + this.selectedTip + this.totalDelivery;
    }
  },
  methods: {

    handleReload(msg) {
      console.log('handleReload checkout', msg);
      this.$emit('reload-send', msg);
    },

    removeItem(item) {
      item.quantity = 0;
      this.updateQuantity(item, 0); // Actualiza el carrito después de establecer la cantidad a 0
    },
    updateQuantity(item, delta) {
      const newQuantity = item.quantity + delta;

      if (newQuantity >= 0) {
        const currentCount = this.cart.filter(i => i.name === item.name && i.price === item.price).length;
        const diff = newQuantity - currentCount;

        if (diff > 0) {
          for (let i = 0; i < diff; i++) {
            this.cart.push({ name: item.name, price: item.price });
          }
        } else if (diff < 0) {
          for (let i = 0; i > diff; i--) {
            const index = this.cart.findIndex(i => i.name === item.name && i.price === item.price);
            if (index !== -1) this.cart.splice(index, 1);
          }
        }
      }
    },
    selectTip(tip) {
      this.selectedTip = tip;
    },
    clearCart() {
      this.cart.splice(0, this.cart.length);
      this.selectedTip = 0;
    }
  },
  data() {
    return {
      selectedTip: 0,
      tipOptions: [],
      deliveryFee: 8.99,
    };
  },
  created() {
    this.tipOptions = [0, 3, 5, 7];
  },
  components: {
    Customer,
    PaymentForm,
  }
};
</script>

<style scoped>
ul {
  list-style-type: none;
  padding: 0;
}
li {
  margin: 10px 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  column-gap: 10px;
}
.checkout {
  border-style: solid;
  border-width: 1px;
  background-color: #8d773c;
  padding: 5px;
  border-radius: 15px;
  box-shadow: 10px;
  color: #FAF7C9;
  padding: 10px;
  box-shadow: 5px 10px 10px 10px #07070730;
  border: 5px outset #d5d0c130;
  min-height: 400px;
}
h3 {
  font-size: 20px;
  text-align: end;
}
.quantity-controls {
  display: flex;
  align-items: center;
}
.quantity-btn {
  background-color: #f0f0f0;
  border: 1px solid #ccc;
  border-radius: 5px;
  padding: 5px 10px;
  cursor: pointer;
}
.quantity-display {
  margin: 0 10px;
  width: 40px;
  text-align: center;
  background-color: #FFFFFF;
  color: #8d773c;
}
span{
  width: 75%;
  font-size: 20px;
}
.tips {
  gap: 20px;
  width: 50px;
  border-radius: 15px;
  margin-left: 10px;
}
.total{
  font-size: 25px;
  margin-top: 10px;
  border-width: 1px;
}
.totales{
  background-color: #0F0F0F;
  padding: 10px;
  color: #f7f7f7;
  border: 5px outset #C0C0C0;
  border-radius: 15px;
}
</style>
