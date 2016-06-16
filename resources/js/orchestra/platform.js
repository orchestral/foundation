import Vue from 'vue'
import Javie from '../vendor/javie'
import Mousetrap from '../vendor/mousetrap'

const traps = {}
const services = {}

class Platform {
  route(route) {
    if (route != null) {
      this.route = route
      Javie.trigger(`orchestra.ready: ${route}`)
    }
  }

  register(name, resolver) {
    services[name] = resolver
  }

  make(name, options = {}) {
    if (_.has(services, name)) {
      return new services[name](options)
    }
  }

  extend(name, to, options) {
    if (_.has(services, name) && services[name] instanceof Vue) {
      this.register(to, services[name].extend(options))
    }
  }

  watch(key, fn) {
    traps[key] = fn
    Mousetrap.bind(key, fn)
  }

  unwatch(key) {
    Mousetrap.unbind(key)
    delete traps[key];
  }
}

export default (new Platform)
