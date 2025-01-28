import  axios from 'axios';

export const listRecipes = async () => {
    return axios(`${process.env.VUE_APP_API_URL}/recipe`)
    .then(response => response)
}

export const getRecipe = async (id) => {
    return axios.get(`${process.env.VUE_APP_API_URL}/recipe/${id}`)
}

export const addRecipe = async (recipe) => {
    return axios.post(`${process.env.VUE_APP_API_URL}/recipe`, recipe)
}

export const editRecipe = async (recipe) => {
    return axios.put(`${process.env.VUE_APP_API_URL}/recipe/${recipe.id}`, recipe)
}

export const deleteRecipe = async (id) => {
    return axios.delete(`${process.env.VUE_APP_API_URL}/recipe/${id}`)
}