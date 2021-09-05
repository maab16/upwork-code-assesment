import * as types from './mutation-types.js'
import Vue from 'vue'

// state
const state = {
    products: [],
    product: null
}

// mutations
const mutations = {
    [types.SET_PRODUCTS] (state, products) {
        state.products = products
    },
    [types.SET_PRODUCT] (state, product) {
        state.product = product
    }
}

// actions
const actions = { 
    async setProduct ({ commit }, id) {
        try {
            commit(types.SET_PRODUCT , null);
            const response = await axios.get(`/api/products/${id}`);

            console.log(response)

            if (response.data.status == 'failed' || response.data.status == 'error') {

                let errors = response.data.data
                console.log(errors)
                if (Array.isArray(errors) && errors.length > 0) {
                    errors.forEach((item, index) => {
                        Vue.notify({
                            type: 'error',
                            title: index,
                            text: item
                        })
                    })
                } else {
                    Vue.notify({
                        type: 'error',
                        text: errors
                    })
                }

                return false
                
            } else {
                commit(types.SET_PRODUCT , response.data.data);
            }
          
        } catch (e) {
            console.log(e);
        }
    },
    async setProducts ({ commit }) {
        try {
            commit(types.SET_PRODUCTS , []);
            const response = await axios.get(`/api/products`);

            console.log(response)

            if (response.data.status == 'failed' || response.data.status == 'error') {

                let errors = response.data.data
                console.log(errors)
                if (Array.isArray(errors) && errors.length > 0) {
                    errors.forEach((item, index) => {
                        Vue.notify({
                            type: 'error',
                            title: index,
                            text: item
                        })
                    })
                } else {
                    Vue.notify({
                        type: 'error',
                        text: errors
                    })
                }

                return false
                
            } else {
                commit(types.SET_PRODUCTS , response.data.data);
            }
          
        } catch (e) {
            console.log(e);
        }
    },
    async create ({commit}, form) {
        try {

            console.log(form)

            const response = await axios.post(`/api/products`, form);

            console.log(response)

            if (response.data.status == 'failed' || response.data.status == 'error') {

                let errors = response.data.data

                if (Array.isArray(errors)) {
                    for (let error of errors) {
                        Vue.notify({
                            type: 'error',
                            text: error
                        })
                    }
                } else {
                    for (let field in errors) {
                        
                        let data = errors[field]
                        
                        if (Array.isArray(data)) {
                            
                            for (let error of data) {

                                console.log(error)

                                Vue.notify({
                                    type: 'error',
                                    text: error
                                })
                            }
                        } else {
                            Vue.notify({
                                type: 'error',
                                text: JSON.stringify(data)
                            })
                        }
                        
                    }
                }

                return false
            }

            Vue.notify({
                type: 'success',
                title: 'Product',
                text: 'Product Created successfully.'
            })

            return true


        } catch (e) {
            console.log(e)
        }
    },
    async update ({commit}, {id, form}) {
        try {

            console.log(id)

            const response = await axios.post(`/api/products/${id}`, form);

            console.log(response)

            if (response.data.status == 'failed' || response.data.status == 'error') {

                let errors = response.data.data

                if (Array.isArray(errors)) {
                    for (let error of errors) {
                        Vue.notify({
                            type: 'error',
                            text: error
                        })
                    }
                } else {
                    for (let field in errors) {
                        
                        let data = errors[field]
                        
                        if (Array.isArray(data)) {
                            
                            for (let error of data) {

                                console.log(error)

                                Vue.notify({
                                    type: 'error',
                                    text: error
                                })
                            }
                        } else {
                            Vue.notify({
                                type: 'error',
                                text: JSON.stringify(data)
                            })
                        }
                        
                    }
                }

                return false
            }

                Vue.notify({
                    type: 'success',
                    title: 'Product',
                    text: 'Product Updated successfully.'
                })

                return true


        } catch (e) {
            console.log(e)
        }
    },
    async delete ({commit}, id) {
        try {

            console.log(id)

            const response = await axios.delete(`/api/products/${id}`);

            console.log(response)

            if (response.data.status == 'failed' || response.data.status == 'error') {

                let errors = response.data.data

                if (Array.isArray(errors)) {
                    for (let error of errors) {
                        Vue.notify({
                            type: 'error',
                            text: error
                        })
                    }
                } else {
                    for (let field in errors) {
                        
                        let data = errors[field]
                        
                        if (Array.isArray(data)) {
                            
                            for (let error of data) {

                                console.log(error)

                                Vue.notify({
                                    type: 'error',
                                    text: error
                                })
                            }
                        } else {
                            Vue.notify({
                                type: 'error',
                                text: JSON.stringify(data)
                            })
                        }
                        
                    }
                }

                return false
            }

                Vue.notify({
                    type: 'success',
                    title: 'Product',
                    text: 'Product Deleted successfully.'
                })

                return true


        } catch (e) {
            console.log(e)
        }
    }
    
}

// getters
const getters = {
    product: (state) => state.product,
    products: (state) => state.products,
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}