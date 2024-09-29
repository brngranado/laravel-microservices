<template>
  <form @submit.prevent="handleSubmit">
    <!-- <div>
      <input class="card-element" type="number" id="name" placeholder="indica la cantidad de platos" required />
    </div> -->
 

    <button class="stripeButton" type="submit" :disabled="isProcessing || invalidZipCode">
      {{ isProcessing ? 'Processing...' : 'Solicitar' }}
    </button>

    <p v-if="errorMessage">{{ errorMessage }}</p>
    <p v-if="paymentSuccess" class="success-message">Payment successful!</p>
  </form>
</template>

<script>
import { addCousine } from '@/services/cousine-service';
import { loadStripe } from '@stripe/stripe-js';
export default {
  props: {
    amount: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      stripe: null,
      elements: null,
      cardElement: null,
      isProcessing: false,
      errorMessage: '',
      paymentSuccess: false,
      address: '',
      invalidZipCode: false,
      autocomplete: null,
    };
  },
  methods: {
    async handleSubmit() {
      this.isProcessing = true;
      const randomOrderNumber = Math.floor(Math.random() * (9999 - 1000 + 1)) + 1000;

      const payload = { order_number: randomOrderNumber };
      await addCousine(payload);
      this.reload();
      this.isProcessing = false;
    },
    reload() {
      console.log('reload en payment');
      this.$emit('reload-send-to-parent', { reload:true  });
    }
  },
  async mounted() {
    this.stripe = await loadStripe('pk_test_51PrKGnHlhI2CMMMUVSLzI5f5svLruNue13g5RWCLlVwVZ0G9piXiE6p2JuEODchkp1SlaRorAC98l9JjK0zyWroA00FSghHWpU');
    this.elements = this.stripe.elements();
    this.cardElement = this.elements.create('card');
    this.cardElement.mount('#card-element');

    if (typeof google !== 'undefined') {
      this.initAutocomplete();
    } else {
      console.error('Google Maps JavaScript API is not loaded.');
    }
  }
};
</script>

<style scoped>
#card-element {
  border: 1px solid #ccc;
  background-color: #FFFFFF;
  padding: 10px;
  border-radius: 4px;
  margin-top: 10px;
  width: 100%;
  padding: 10px;
  box-sizing: border-box;
}
.stripeButton {
  margin-top: 20px;
  border-radius: 15px;
  background-color: #070707;
  color: #FFFFFF;
  width: 100%;
  height: 30px;
}
.card-element {
  border: 1px solid #ccc;
  background-color: #FFFFFF;
  padding: 10px;
  border-radius: 4px;
  margin-top: 10px;
  width: 100%;
  padding: 10px;
  box-sizing: border-box;
}
.error-message {
  color: #FAF7C9;
  margin-top: 5px;
}
.success-message {
  color: #FAF7C9;
  margin-top: 5px;
}
</style>
