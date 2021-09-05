import Vue from 'vue'
import Vuex from 'vuex'
import auth from './modules/auth'
import product from './modules/products/product.js'

Vue.use(Vuex)

const modules = {
  auth,
  product
}

export default new Vuex.Store({
  modules
})