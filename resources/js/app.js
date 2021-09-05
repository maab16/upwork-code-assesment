/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue'
import store from './store'
import router from './router'
import App from './components/App'
import vuetify from './plugins/vuetify'
import Notifications from 'vue-notification'

Vue.use(Notifications)

 new Vue({
    el: '#app', // This should be the same as your <div id=""> from earlier.
    vuetify,
    store,
    router,
    ...App
})
