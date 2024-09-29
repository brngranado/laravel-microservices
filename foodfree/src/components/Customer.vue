<template>
    <div>
      <form @submit.prevent="stripeButton">
        <div>
          <label for="name">Name:</label>
          <input type="text" id="name" v-model="formData.name" required />
        </div>
  
        <div>
          <label for="phone">Phone Number:</label>
          <input type="tel" id="phone" v-model="formData.phone" required />
        </div>
  
        <div>
          <label for="email">Email:</label>
          <input type="email" id="email" v-model="formData.email" required />
        </div>
  
        <div>
          <label for="address">Address:</label>
          <input id="address" v-model="formData.address" required></input>
        </div>

        <div>
          <label for="deliveryNotes">Delivery Notes:</label>
          <textarea id="deliveryNotes" v-model="formData.address" required></textarea>
        </div>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        formData: {
          name: '',
          phone: '',
          email: '',
          address: '',
        },
      };
    }
  };
  </script>
  
  <style scoped>
  /* Estilos básicos para el formulario */
  form {

  }
  
  div {
    margin-bottom: 15px;
  }
  
  label {
    font-weight: bold;
    margin-bottom: 5px;
  }
  
  input,
  textarea {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
  }
  
  button {
    padding: 10px;
    background-color: #42b983;
    color: white;
    border: none;
    cursor: pointer;
  }
  
  button:hover {
    background-color: #369370;
  }
  </style>
  