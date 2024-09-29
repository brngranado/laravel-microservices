<template>
    <div>
      <h3>Cotización de Envío</h3>
      <p v-if="loading">Obteniendo cotización...</p>
      <p v-if="quote">La cotización para la dirección {{ address }} es: {{ quote }} USD</p>
      <p v-if="error" style="color: red;">Error obteniendo la cotización: {{ error }}</p>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      address: {
        type: String,
        required: true,
      },
    },
    data() {
      return {
        quote: null,
        loading: false,
        error: null,
      };
    },
    watch: {
      address: {
        immediate: true,
        handler(newAddress) {
          if (newAddress) {
            this.getQuote(newAddress);
          }
        },
      },
    },
    methods: {
      async getQuote(address) {
        this.loading = true;
        this.error = null;
        try {
          // Llamada a la API de Uber Deliveries
          const response = await fetch('https://api.uber.com/v1/customers/YOUR_CUSTOMER_ID/delivery_quotes', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              Authorization: `IA.VUNmGAAAAAAAEgASAAAABwAIAAwAAAAAAAAAEgAAAAAAAAGgAAAAFAAAAAAADgAQAAQAAAAIAAwAAAAOAAAAdAAAABwAAAAEAAAAEAAAAGsqIDGMQehx6MHF1VHn1UFNAAAAYr0GeO0FqVehXsAXMJLcp7h9VO4pK2cdBXEot7yQEJ_aGSi3p6zNLHlmb76NnZ9Bb-9n8GCL14z08gJPGLvFdveAf6sSBqBx9ZqXjPoAAAAMAAAA2hpi93i8aOSjyZGFJAAAAGIwZDg1ODAzLTM4YTAtNDJiMy04MDZlLTdhNGNmOGUxOTZlZQ`,
            },
            body: JSON.stringify({
              pickup: {
                latitude: 37.7758, // Latitude de ejemplo, debe ajustarse según tus necesidades
                longitude: -122.4183, // Longitude de ejemplo
              },
              dropoff: {
                address: address,
              },
              delivery_type: 'standard', // Tipo de entrega
            }),
          });
  
          const data = await response.json();
  
          if (response.ok) {
            this.quote = data.quote_id; // Asumiendo que `quote_id` o algún otro campo relevante contiene la cotización
          } else {
            this.error = data.message || 'Error desconocido al obtener la cotización';
          }
        } catch (err) {
          this.error = err.message;
        } finally {
          this.loading = false;
        }
      },
    },
  };
  </script>
  
  <style scoped>
  /* Estilos opcionales para el componente de cotización */
  </style>
  