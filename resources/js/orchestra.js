import Vue from 'vue'
import Resource from 'vue-resource'
import Platform from './orchestra/platform'
import App from './orchestra/app'
import Dashboard from './orchestra/pages/dashboard'
import Setting from './orchestra/pages/setting'

Vue.use(Resource)

Platform.register('app', App)
Platform.register('dashboard', Dashboard)
Platform.register('setting', Setting)

window.Vue = Vue
window.Platform = Platform

require('./bootstrap')
