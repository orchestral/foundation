import Vue from 'vue'
import Resource from 'vue-resource'
import Platform from './orchestra/platform'
import App from './kite/app'

Vue.use(Resource)

window.Vue = Vue
window.Platform = Platform
window.App = App

require('./orchestra/bootstrap')
