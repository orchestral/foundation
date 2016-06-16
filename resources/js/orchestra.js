import Vue from 'vue'
import Resource from 'vue-resource'
import Platform from './orchestra/platform'
import Setting from './orchestra/setting'
import App from './orchestra/app'

Vue.use(Resource)

Platform.register('app', App)
Platform.register('setting', Setting)

window.Vue = Vue
window.Platform = Platform

require('./bootstrap')
