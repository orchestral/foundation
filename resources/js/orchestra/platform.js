import Javie from '../vendor/javie'
import Mousetrap from '../vendor/mousetrap'

const traps = {}

class Platform {
  route(route) {
    if (route != null) {
      this.route = route
      Javie.trigger(`orchestra.ready: ${route}`)
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
