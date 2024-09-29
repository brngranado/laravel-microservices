import { createApp } from 'vue';
import App from './App.vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

// Crear la aplicación y usar BootstrapVueNext
const app = createApp(App);
app.mount('#app');

//Keys Stripe

const stripePublicKey = process.env.VUE_APP_STRIPE_PUBLIC_KEY;
const stripeSecretKey = process.env.VUE_APP_STRIPE_SECRET_KEY;

// Utiliza las keys donde las necesites
console.log(stripePublicKey);
console.log(stripeSecretKey);
