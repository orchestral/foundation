import _ from '../../vendor/underscore'

class CountTo {
  constructor(value, fn, options = {}) {
    this.count = 0
    this.value = value
    this.fn = fn
    this.options = _.defaults(options, {
      speed: 1000,
      refreshInterval: 100
    })
  }

  start() {
    let loops = Math.ceil(this.options.speed / this.options.refreshInterval)
    this.increment = Math.floor(this.value / loops)

    if (this.increment < 1) {
      this.increment = 1
    }

    this.interval = setInterval(this.update.bind(this), this.options.refreshInterval)
  }

  update() {
    if (this.count < this.value) {
      let diff = this.count + this.increment

      this.count = (diff > this.value) ? this.value : diff
    } else {
      this.count = this.value
      clearInterval(this.interval)
    }

    if(_.isFunction(this.fn)) {
      this.fn(this)
    }
  }
}

export default CountTo
