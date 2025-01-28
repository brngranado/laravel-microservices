import axios from 'axios';

export const listGroceries = async () => {
    return axios(`${process.env.VUE_APP_API_URL}/grocery`)
        .then(response => response)
}

export const addgrocery = async (grocery) => {
    return axios.post(`${process.env.VUE_APP_API_URL}/grocery`, grocery)
}

export const deletegrocery = async (id) => {
    return axios.delete(`${process.env.VUE_APP_API_URL}/grocery/${id}`)
}

export const editgrocery = async (grocery) => {
    return axios.put(`${process.env.VUE_APP_API_URL}/grocery/${grocery.id}`, grocery)
}

export const getgrocery = async (id) => {
    return axios.get(`${process.env.VUE_APP_API_URL}/grocery/${id}`)
}


export const buyMarket = async (ingredient) => {
    return axios.get(`https://recruitment.alegra.com/api/farmers-market/buy?ingredient=${ingredient}`)
}