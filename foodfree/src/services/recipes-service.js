import  axios from 'axios';

export const listRecipes = async () => {
    return axios('http://api-manager.test/api/recipe')
    .then(response => response)
}

export const getRecipe = async (id) => {
    return axios.get(`http://api-manager.test/api/recipe/${id}`)
}

export const addRecipe = async (recipe) => {
    return axios.post('http://api-manager.test/api/recipe', recipe)
}

export const editRecipe = async (recipe) => {
    return axios.put(`http://api-manager.test/api/recipe/${recipe.id}`, recipe)
}

export const deleteRecipe = async (id) => {
    return axios.delete(`http://api-manager.test/api/recipe/${id}`)
}