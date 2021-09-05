import * as types from './mutation-types.js'

// state
const state = {
    user: null
}

// mutations
const mutations = {
    [types.SET_USER] (state, user) {
        state.user = user
    }
}

// actions
const actions = { 
    async login ({ commit }) {
        try {
            commit(types.SET_USER , null);
            await axios.get('/sanctum/csrf-cookie').then(async (res) => {
                await axios.get('/api/user').then(response => {
                    console.log(response)
                    if (response.data.status === 'success') {
                        commit(types.SET_USER, response.data.user)
                    }
                }).catch(error => {
                    // 
                    if (error.response) {
                        // Request made and server responded
                        console.log(error.response.data);
                        console.log(error.response.status);
                        console.log(error.response.headers);

                        if (error.response.status == 401) {
                            window.location.replace( window.location.origin + '/login')
                        }
                      } else if (error.request) {
                        
                      } else {
                        window.location.replace( window.location.origin + '/login')
                      }
                })
            })

          
        } catch (e) {
            console.log(e);
        }
    },
    async setUser ({ commit }) {
        try {
            commit(types.SET_USER , null);
            await axios.get('/api/user').then(response => {
                console.log(response)
                if (response.data.status === 'success') {
                    commit(types.SET_USER, response.data.user)
                }
            }).catch(error => {
                // 
                if (error.response) {
                    // Request made and server responded
                    console.log(error.response.data);
                    console.log(error.response.status);
                    console.log(error.response.headers);

                    if (error.response.status == 401) {
                        window.location.replace( window.location.origin + '/login')
                    }
                  } else if (error.request) {
                    
                  } else {
                    window.location.replace( window.location.origin + '/login')
                  }
            })

          
        } catch (e) {
            console.log(e);
        }
    },
}

// getters
const getters = {
    user: (state) => state.user,
    check: (state) => state.user === null ? true : false,
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}