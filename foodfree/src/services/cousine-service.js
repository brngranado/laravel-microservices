import  axios from 'axios';


export const  listCousines = async () => {
    return axios(`${process.env.VUE_APP_API_URL}cousine`)
        .then(response => response)
}

export const getCousine = async (id) => {
    return axios.get(`${process.env.VUE_APP_API_URL}/${id}`)
}

export const addCousine = async (cousine) => {
    return axios.post(`${process.env.VUE_APP_API_URL}/cousine`, cousine)
}

export const editCousine = async (cousine) => {
    console.log('antes de enviar',cousine);
    return axios.put(`${process.env.VUE_APP_API_URL}/cousine/${cousine.id}`, cousine)
}

export const editCousineWithIngredientsUpdated = async (cousine) => {
    return axios.put(`${process.env.VUE_APP_API_URL}/cousine-ingredient-updated/${cousine.id}`, cousine)
}

export const deleteCousine = async (id) => {
    return axios.delete(`${process.env.VUE_APP_API_URL}/cousine/${id}`)
}