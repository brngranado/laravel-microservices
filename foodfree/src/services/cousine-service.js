import  axios from 'axios';


export const  listCousines = async () => {
    return axios('http://api-manager.test/api/cousine')
        .then(response => response)
}

export const getCousine = async (id) => {
    return axios.get(`http://api-manager.test/api/cousine/${id}`)
}

export const addCousine = async (cousine) => {
    return axios.post(`http://api-manager.test/api/cousine`, cousine)
}

export const editCousine = async (cousine) => {
    console.log('antes de enviar',cousine);
    return axios.put(`http://api-manager.test/api/cousine/${cousine.id}`, cousine)
}

export const deleteCousine = async (id) => {
    return axios.delete(`http://api-manager.test/api/cousine/${id}`)
}