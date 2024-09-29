<template>
  <div class="menu">
    <details class="category">
      <summary>En cocina</summary>
      <div class="menu-items grid-layout">
        <button @click=" item.status_id === 6 && updateCousineStatusFinalize(item)" v-for="item in cousine" :key="item.id"  class="menu-button">
          <img :src="item.status_id === 5 ? '/cocinando.jpg' : '/platillo.jpg'" alt="" class="item-image"/>
          <span  class="item-title">N°{{ item.order_number }} - {{ item.status_id ===  5 ? 'pendiente': 'despachar' }}</span>
        </button>
      </div>
    </details>
    <hr>
    <details class="category">
      <summary>Bodega</summary>
      <div class="menu-items grid-layout">
        <button v-for="item in groceries" :key="item.id"  class="menu-button">
          <img :src="item.image" alt="" class="item-image"/>
          <span class="item-title">{{ item.ingredient }} -  qty:{{ item.quantity }}</span>
        </button>
      </div>
    </details>
    <hr>
    <details class="category">
      <summary>Recetas</summary>
      <div class="menu-items grid-layout">
        <button v-for="item in recipes" :key="item.id" @click="openRecipeModal(item)" class="menu-button">
          <img width="100px"  :src="'/receta.png'" alt="" class="item-image'"/>
          <span class="item-title">{{ item.name }}</span>
        </button>
      </div>
    </details>
    <div v-if="modal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content">
        <h2>Ingredientes</h2>
        <ul>
          <li v-for="ingredient in selectedRecipe" :key="ingredient.id">
            {{ ingredient.ingredientName }} - qty: {{ ingredient.quantity }}
          </li>
        </ul>
        <!-- Add more recipe details as needed -->
        <button @click="closeModal">Close</button>
      </div>
    </div>

  </div>
</template>

<script>
import { listCousines, editCousine} from '../services/cousine-service.js';
import { listGroceries} from '../services/groceries-service.js';
import{listRecipes} from '../services/recipes-service.js';

export default {
  name: 'Menu',
  props: {
  reload: {
    type: Boolean,
    default: false
  }
},
  data() {
    return {
      cousine:[],
      groceries: [],
      recipes: [],
      modal: false,
      selectedRecipe: {},
    };
  },
  methods: {
    openRecipeModal(recipe) {
    this.selectedRecipe = recipe.ingredients
        .map(item => {
            const grocery = this.groceries.find(i => i.code === item.grocery_store_id);
            return {
                ...item,
                ingredientName: grocery ? grocery.ingredient : 'No existe',
            };
        })
        .filter(item => item.ingredientName !== 'No existe');
    this.modal = true; 
},

  async updateCousineStatus() {
      const itemsToUpdate = this.cousine.filter(item => item.status_id === 5);
      if (itemsToUpdate.length > 0) {
        const updatePromises = itemsToUpdate.map(item => {
          return editCousine({id: item.id, status_id: 6 }); // Change 1 to whatever "ready" status is
        });
        await Promise.all(updatePromises);
        await this.getListCousine();
      }
    },

    async updateCousineStatusFinalize(item) {
        await editCousine({id: item.id, status_id: 4 })
        await this.getListCousine();
    },

    closeModal() {
      this.modal = false; 
    },
    async getListCousine() {
    const cousines = await  listCousines();
    this.cousine = cousines.data;
    },
    async getListGroceries() {
    const groceries = await  listGroceries();
    const groceriesModified = groceries.data.map((grocery) => {
      if (grocery.ingredient === 'tomato') {
        return {
          ...grocery,
          image: '/tomate.jpg',
        }
      }
      if (grocery.ingredient === 'lemon') {
        return {
          ...grocery,
          image: '/limon.png',
        }
      }
      if (grocery.ingredient ==='potato') {
        return {
          ...grocery,
          image: 'papa.png',
        }
      }
      if (grocery.ingredient === 'rice') {
        return {
          ...grocery,
          image: '/arroz.png',
        }
      }
      if (grocery.ingredient === 'ketchup') {
        return {
          ...grocery,
          image: '/keptchup.png',
        }
      }
      if (grocery.ingredient === 'lettuce') {
        return {
          ...grocery,
          image: '/lechuga.jpg',
        }
      }
      if (grocery.ingredient === 'onion') {
        return {
          ...grocery,
          image: '/cebolla.jpg',
        }
      }
      if (grocery.ingredient === 'cheese') {
        return {
          ...grocery,
          image: '/queso.png',
        }
      }
      if (grocery.ingredient =='meat') {
        return {
          ...grocery,
          image: 'carne.png',
        }
      }
      if (grocery.ingredient =='chicken') {
        return {
          ...grocery,
          image: '/pollo.png',
        }
      }
    })
    this.groceries = groceriesModified;
    },

    async getListRecipes() {
    const recipes = await listRecipes();
    this.recipes = recipes.data;
    },
    async fetchAllLists() {
    await Promise.all([
      this.getListCousine(),
      this.getListGroceries(),

    ]);
  }

  },
  mounted() {
    this.getListCousine();
    this.getListGroceries();
    this.getListRecipes();
    this.updateInterval = setInterval(() => {
      console.log('Updating status...');
      this.updateCousineStatus();
    }, 120000);
  },

  watch: {
  reload(newValue) {
    console.log('reload menu', newValue);
    if (newValue === true) {
      this.fetchAllLists(); // Reload all lists when reload is true
    }
  }
}
};
</script>

<style scoped>
.menu {
  border-style: solid;
  border-width: 1px;
  background-color: #FAF7C9;
  padding: 5px 2px 5px 2px;
  border-radius: 15px;
  box-shadow: 10px;
  color: #8d773c;
  padding: 10px;
  box-shadow: 5px 10px 10px 10px #07070750;
  justify-content: center;
  align-items: center;
  border: 5px outset #d5d0c130;
}

.category {
  margin-bottom: 20px;
  font-size: 30px
}

.grid-layout {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 15px;
}

.menu-button {
  display: flex;
  flex-direction: column;
  align-items: center;
  color: #8d773c;
  padding: 10px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  text-align: center;
  border: 5px outset #d5d0c130;
  box-shadow: 2px 3px 3px 3px #07070730;
}

.menu-button .item-image {
  width: 100px;
  height: 100px;
  margin-bottom: 10px;
  border-radius: 5px;
}

.menu-button .item-title {
  font-size: 14px;
  background-color: #8d773c;
  color: #FAF7C9;
  padding-left: 10px;
  padding-right: 10px;
  border-radius: 15px;
}

.menu-button:hover {
  background-color: #8d773c;
  color: #FAF7C9;
}

/* Responsividad */
@media (max-width: 1200px) {
  .grid-layout {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .grid-layout {
    grid-template-columns: 1fr;
  }

  .menu {
    padding: 5px;
  }

  .menu-button .item-image {
    width: 80px;
    height: 80px;
  }

  .menu-button {
    padding: 8px;
    font-size: 14px;
  }

  .menu-button .item-title {
    font-size: 15px;
  }
}

@media (max-width: 480px) {
  .imagen img {
    width: 150px;
    height: 150px;
  }

  .menu-button {
    padding: 5px;
    font-size: 12px;
  }

  .menu-button .item-image {
    width: 60px;
    height: 60px;
  }

  .menu-button .item-title {
    font-size: 10px;
  }
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  max-width: 500px;
  max-height: 80vh; /* Limit height for scrolling */
  overflow-y: auto; /* Enable scrolling */
}

.modal-content h2 {
  margin-top: 0;
}
</style>
