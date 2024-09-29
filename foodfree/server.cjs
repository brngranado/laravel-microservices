const cors = require('cors');
const express = require('express');
const morgan = require('morgan');
const dotenv = require('dotenv');
const fetch = require('node-fetch');  // Importar fetch para hacer la solicitud a Zapier

dotenv.config();

const app = express();

// Usar la clave secreta de Stripe desde la variable de entorno
const stripe = require('stripe')(process.env.VUE_APP_STRIPE_SECRET_KEY);

app.use(express.json());
app.use(cors());
app.use(morgan('combined'));

// Ruta para crear el Payment Intent de Stripe
app.post('/create-payment-intent', async (req, res) => {
  try {
    const { amount, paymentMethodId } = req.body;

    console.log('Creating PaymentIntent with amount:', amount);

    // Crear un PaymentIntent con Stripe
    const paymentIntent = await stripe.paymentIntents.create({
      amount: amount,
      currency: 'usd',
      payment_method: paymentMethodId,
      confirmation_method: 'manual',
      confirm: true,
      return_url: 'https://example.com/'  // Puedes cambiar esto a tu URL real
    });

    console.log('PaymentIntent created successfully:', paymentIntent.id);

    // Responder con el client_secret
    res.send({ client_secret: paymentIntent.client_secret });
  } catch (e) {
    console.error('Error creating PaymentIntent:', e.message);
    res.send({ error: e.message });
  }
});

// Nueva ruta para enviar datos a Zapier
app.post('/send-to-zapier', async (req, res) => {
  const zapierWebhookUrl = 'https://hooks.zapier.com/hooks/catch/20146479/2ddp7qz/'; // Reemplaza con tu URL de Zapier

  try {
    // Obtener los datos del cuerpo de la solicitud (formulario)
    const { name, phone, email, address, deliveryNotes } = req.body;

    // Formatear los datos para enviar a Zapier
    const formData = {
      name,
      phone,
      email,
      address,
      deliveryNotes,
    };

    // Hacer la solicitud a Zapier
    const response = await fetch(zapierWebhookUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(formData),
    });

    if (!response.ok) {
      throw new Error(`Zapier webhook failed: ${response.statusText}`);
    }

    console.log('Data sent to Zapier successfully');
    res.status(200).json({ message: 'Data sent to Zapier successfully' });
  } catch (error) {
    console.error('Error sending data to Zapier:', error.message);
    res.status(500).json({ error: error.message });
  }
});

// Inicia el servidor en el puerto 4242
app.listen(4242, () => console.log('Server running on port 4242'));
