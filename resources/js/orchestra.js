import Vue from 'vue'
import Resource from 'vue-resource'
import Platform from './orchestra/platform'
import Setting from './orchestra/setting'
import App from './orchestra/app'

Vue.use(Resource)

window.Vue = Vue
window.Platform = Platform
window.Setting = Setting
window.App = App

require('./orchestra/bootstrap')
