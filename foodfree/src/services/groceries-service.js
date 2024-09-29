import axios from 'axios';

export const listGroceries = async () => {
    return axios('http://api-manager.test/api/grocery')
        .then(response => response)
}

export const addgrocery = async (grocery) => {
    return axios.post('http://api-manager.test/api/grocery', grocery)
}   

export const deletegrocery = async (id) => {
    return axios.delete(`http://api-manager.test/api/grocery/${id}`)
}

export const editgrocery = async (grocery) => {
    return axios.put(`http://api-manager.test/api/grocery/${grocery.id}`, grocery)
}

export const getgrocery = async (id) => {
    return axios.get(`http://api-manager.test/api/grocery/${id}`)
}