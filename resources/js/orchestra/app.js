import Javie from '../vendor/javie'

class OP {
  route(route) {
    if (route != null) {
      this.route = route
      Javie.trigger(`orchestra.ready: ${route}`)
    }
  }
}

export default OP
