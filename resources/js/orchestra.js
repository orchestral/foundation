import Vue from 'vue'
import OP from './orchestra/app'
import App from './kite/app'

window.Vue = Vue
window.OP = new OP
window.App = App
